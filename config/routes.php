<?php
/**
 * Created by PhpStorm.
 * User: Vancior
 * Date: 2017/10/21
 * Time: 0:00
 */

use NoahBuscher\Macaw\Macaw;

Macaw::get('api/q(:num)', 'HomeController@home');
Macaw::post('api/user', 'UserController@signUp');
Macaw::post('api/session', 'UserController@login');
Macaw::delete('api/session', 'UserController@logout');
Macaw::get('api/session/user', 'UserController@autoLogin');
Macaw::get('api/user/(:num)', 'UserController@info');
Macaw::post('api/project', 'ProjectController@create');
Macaw::get('api/project/(:num)/issue', 'IssueController@issue');

//Macaw::get('/(:num)', function ($fu) {
//  echo $fu;
//});

//Macaw::get('p(:num)', function ($fu) {
//  echo $fu;
//});

//Macaw::get('test', function () {
//  html('test');
//});

//Macaw::get('(:all)', function ($file) {
//  html($file);
//});
//
Macaw::$error_callback = function () {
  throw new Exception('404 Not Found');
};

Macaw::dispatch();