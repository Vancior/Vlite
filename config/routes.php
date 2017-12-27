<?php
/**
 * Created by PhpStorm.
 * User: Vancior
 * Date: 2017/10/21
 * Time: 0:00
 */

use NoahBuscher\Macaw\Macaw;

Macaw::get('api/(:num)', 'HomeController@home');
Macaw::post('api/user', 'UserController@signUp');
Macaw::post('api/session', 'UserController@login');
Macaw::delete('api/session', 'UserController@logout');
Macaw::get('api/session/user', 'UserController@autoLogin');
Macaw::get('api/user/(:num)', 'UserController@info');
Macaw::post('api/project', 'ProjectController@create');
Macaw::get('api/project/(:num)/issue', 'IssueController@issue');
Macaw::post('api/project/(:num)/issue', 'IssueController@create');
Macaw::get('api/user/todo', 'TodoController@todo');
Macaw::get('api/issue/(:num)', 'IssueController@info');
Macaw::post('api/issue/(:num)/comment', 'CommentController@create');
Macaw::get('api/project/search', 'ProjectController@search');
Macaw::get('project/(:num)/download', 'ProjectController@download');
Macaw::get('project/(:num)/upload', 'ProjectController@upload');

/*
 * Warning: 这里不要直接使用(:all)，并且使用(:any)的情况下也有可能产生冲突
 * 最好访问html不要使用二级目录，使用二级时手动分发url
*/
Macaw::get('(:any)', function ($file) {
  html($file);
});

Macaw::get('user/(:num)', function ($file) {
  html('user');
});

Macaw::get('project/(:num)', function ($file) {
  html('project');
});

Macaw::get('issue/(:num)', function ($file) {
  html('issue');
});

Macaw::get('js/(:all)', function ($file) {
  js($file);
});

Macaw::get('css/(:all)', function ($file) {
  css($file);
});

Macaw::get('images/(:all)', function ($file) {
  image($file);
});

//Macaw::get('(:all)', function ($file) {
//  require_once BASE_PATH . '/' . $file;
//});

Macaw::error(function () {
//  throw new Exception('404 Not Found');
  header('HTTP/1.1 404 Not Found');
});

Macaw::dispatch();