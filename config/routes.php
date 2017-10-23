<?php
/**
 * Created by PhpStorm.
 * User: Vancior
 * Date: 2017/10/21
 * Time: 0:00
 */

use NoahBuscher\Macaw\Macaw;

$V = 'Vlite\\Controller\\';

Macaw::get('home', 'HomeController@home');

Macaw::get('p(:num)', function ($fu) {
  echo $fu;
});

Macaw::$error_callback = function () {
  throw new Exception('404 Not Found');
};

Macaw::dispatch();