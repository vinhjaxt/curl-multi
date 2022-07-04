# curl-multi
PHP8 curl multi
```php
include_once 'curl_multi.php';
$lastTime = microtime(true);
while (true) {
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
        // CURLOPT_VERBOSE => true,
        // CURLOPT_STDERR => fopen(dirname(__FILE__) . '/errorlog.txt', 'a+'),
        CURLOPT_STDERR => fopen('php://stderr', 'a+'),
      ]
    ],
    [
      'headers' => [
        'A: B',
        'C' => 'D',
      ],
      'url' => 'http://xn--lun-lna.vn',
      'follow' => true,
      'form' => http_build_query(['a' => 'b', 'c' => 'd']),
      'opts' => [
        CURLOPT_CUSTOMREQUEST => 'PUT',
        // CURLOPT_VERBOSE => true,
        // CURLOPT_STDERR => fopen(dirname(__FILE__) . '/errorlog.txt', 'a+'),
        CURLOPT_STDERR => fopen('php://stderr', 'a+'),
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
        // CURLOPT_VERBOSE => true,
        CURLOPT_STDERR => fopen('php://stderr', 'a+'),
      ]
    ],
  ], [
    CURLMOPT_MAX_TOTAL_CONNECTIONS => 3,
    CURLMOPT_MAX_HOST_CONNECTIONS => 3,
    CURLMOPT_PIPELINING => 2,
  ]);

  echo 'Done: ', microtime(true) - $lastTime, PHP_EOL;
  $lastTime = microtime(true);
}


```

## Parallel
```php
include_once 'curl_multi_parallel.php';

$lastTime = microtime(true);
do {
  $resps = curl_multi_parallel(function ($resp) {
    var_dump($resp);
  }, [
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
        // CURLOPT_VERBOSE => true,
        // CURLOPT_STDERR => fopen(dirname(__FILE__) . '/errorlog.txt', 'a+'),
        CURLOPT_STDERR => fopen('php://stderr', 'a+'),
      ]
    ],
    [
      'headers' => [
        'A: B',
        'C' => 'D',
      ],
      'url' => 'http://xn--lun-lna.vn',
      'follow' => true,
      'form' => http_build_query(['a' => 'b', 'c' => 'd']),
      'opts' => [
        CURLOPT_CUSTOMREQUEST => 'PUT',
        // CURLOPT_VERBOSE => true,
        // CURLOPT_STDERR => fopen(dirname(__FILE__) . '/errorlog.txt', 'a+'),
        CURLOPT_STDERR => fopen('php://stderr', 'a+'),
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
        // CURLOPT_VERBOSE => true,
        CURLOPT_STDERR => fopen('php://stderr', 'a+'),
      ]
    ],
  ], 2, [
    CURLMOPT_MAX_TOTAL_CONNECTIONS => 3,
    CURLMOPT_MAX_HOST_CONNECTIONS => 3,
    CURLMOPT_PIPELINING => 2,
  ]);

  echo 'Done: ', microtime(true) - $lastTime, PHP_EOL;
  $lastTime = microtime(true);
} while (true);
```

## Parallel func
```php
include_once './curl_multi_parallel_func.php';
do {
  $startTime = microtime(true);
  $count = 0;
  curl_multi_parallel_func(function ($resp) use (&$count) {
    var_dump($resp);
    if ($count == 2) {
      var_dump('AAAAAAAAAAAAAAAAAAAAAAAAA');
      sleep(10);
    }
  }, function () use (&$count) {
    if ($count > 2) return null;
    var_dump($count);
    $count++;
    return [
      [
        'url' => 'http://1.1.1.1',
        'follow' => true,
        'timeout' => 5,
      ],
      [
        'url' => 'http://xn--lun-lna.vn',
        'timeout' => 5,
      ],
      [
        'url' => 'http://echo.opera.com',
        'timeout' => 5,
      ],
    ][rand(0, 2)];
  }, 2, [
    CURLMOPT_MAX_TOTAL_CONNECTIONS => 3,
    CURLMOPT_MAX_HOST_CONNECTIONS => 3,
    CURLMOPT_PIPELINING => 2,
  ]);

  echo 'Done: ', microtime(true) - $startTime, PHP_EOL;
} while (false);

```
