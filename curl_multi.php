<?php

function curl_multi($requests = [], $mopts = [])
{
  $chs = [];
  $mh = curl_multi_init();

  if (is_array($mopts))
    foreach ($mopts as $mopt => $mval) {
      curl_multi_setopt($mh, $mopt, $mval);
    }

  foreach ($requests as $rk => $request) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $request['url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:993.0) Gecko/20100101 Firefox/993.0');
    curl_setopt($ch, CURLOPT_PATH_AS_IS, true);
    curl_setopt($ch, CURLOPT_DNS_SERVERS, '1.1.1.1,8.8.8.8,1.0.0.1,8.8.4.4,1.1.1.2');
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    if (defined('CURLOPT_SAFE_UPLOAD'))
      curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);

    if (@$request['follow']) {
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_MAXREDIRS, 20);
    }

    if (@$request['no_verify']) {
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }

    if (isset($request['timeout'])) {
      curl_setopt($ch, CURLOPT_TIMEOUT, $request['timeout']);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $request['timeout']);
    }

    $contentType = false;
    if (@$request['json']) {
      if (!is_bool($request['json'])) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($request['json']) ? json_encode($request['json']) : $request['json']);
        $contentType = 'application/json';
      }
    }

    if (!isset($request['form']) && @$request['data']) $request['form'] = $request['data'];

    if (@$request['form']) {
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $request['form']);
      // upload file: POSTFIELDS element: new CurlFile('filename.png', 'image/png')
      // curl default: POSTFIELDS array => 'multipart/form-data'; POSTFIELDS string => 'application/x-www-form-urlencoded'
    }

    $headers = [];
    if (@$request['headers']) {
      foreach ($request['headers'] as $hk => $hv) {
        if (is_string($hk)) {
          if ($contentType && strtolower($hk) === 'content-type') $contentType = false;
          $headers[] = $hk . ': ' . $hv;
        } else {
          if ($contentType && stripos($hv, 'content-type:') === 0) $contentType = false;
          $headers[] = $hv;
        }
      }
    }

    if ($contentType) {
      $headers[] = 'Content-Type: ' . $contentType;
    }

    if (isset($headers[0]))
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if (@$request['opts']) {
      // raw curl opts
      foreach ($request['opts'] as $ok => $ov) {
        curl_setopt($ch, $ok, $ov);
      }
    }

    $chs[spl_object_id($ch)] = $rk;
    curl_multi_add_handle($mh, $ch);
  }

  // execute the multi handle
  do {
    $status = curl_multi_exec($mh, $active);
    if ($active) {
      // wait a short time for more activity
      curl_multi_select($mh);
    }
  } while ($active && $status == CURLM_OK);

  $resps = [];
  $queued_messages = 0;
  do {
    $mh_info = curl_multi_info_read($mh, $queued_messages);
    if (!$mh_info) break;

    $ch = $mh_info['handle'];
    $chk = $chs[spl_object_id($ch)];

    $err = false;
    if ($mh_info['result'] !== CURLE_OK) {
      $err = curl_error($ch);
    }

    $info = curl_getinfo($ch);
    $data = curl_multi_getcontent($ch);
    $header = substr($data, 0, $info['header_size']);
    $data = substr($data, $info['header_size']);

    if (@$requests[$chk]['json'] && @$requests[$chk]['no_parse_json'] !== true && in_array(trim(explode(';', $info['content_type'])[0]), ['application/json'])) {
      $data = json_decode($data, true);
    }

    $resps[$chk] = [
      'info' => $info,
      'data' => $data,
      'header' => $header,
      'error' => $err,
    ];

    // close the handles
    curl_multi_remove_handle($mh, $ch);
    unset($ch);
  } while ($queued_messages > 0);

  curl_multi_close($mh);
  return $resps;
}
