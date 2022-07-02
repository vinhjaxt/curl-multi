# curl-multi
PHP curl multi
```php

$resps = curl_multi([
  [
    'headers' => [
      'A: B',
      'C' => 'D',
    ],
    'url' => 'http://1.1.1.1',
    'follow' => true,
    'form' => http_build_query(['a' => 'b', 'c' => 'd']),
    'opts' => [
      CURLOPT_CUSTOMREQUEST => 'PUT',
      CURLOPT_VERBOSE => true,
      CURLOPT_STDERR => fopen(dirname(__FILE__) . '/errorlog.txt', 'a+'),
    ]
  ],
  'echo.opera.com' => [
    'headers' => [
      'A: B',
      'C' => 'D',
    ],
    'url' => 'http://echo.opera.com',
    'no_verify' => true,
    'form' => http_build_query(['a' => 'b', 'c' => 'd']),
    'opts' => [
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_VERBOSE => true,
      CURLOPT_STDERR => fopen('php://stderr', 'a+'),
    ]
  ],
], [
  CURLMOPT_MAX_TOTAL_CONNECTIONS => 2,
  CURLMOPT_MAX_HOST_CONNECTIONS => 2,
  CURLMOPT_PIPELINING => 2,
]);

foreach ($resps as $k => $resp) {
  var_dump($k, $resp);
}

```
