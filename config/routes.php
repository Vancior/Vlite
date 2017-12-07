<?php
/**
 * Created by PhpStorm.
 * User: Vancior
 * Date: 2017/10/21
 * Time: 0:00
 */

use NoahBuscher\Macaw\Macaw;

Macaw::get('api/q(:num)', 'HomeController@home');

Macaw::get('/(:num)', function ($fu) {
  echo $fu;
});

Macaw::get('p(:num)', function ($fu) {
  echo $fu;
});

Macaw::get('test', function () {
  html('test');
});

Macaw::$error_callback = function () {
  throw new Exception('404 Not Found');
};

Macaw::dispatch();