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
