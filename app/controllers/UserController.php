<?php
/**
 * Created by PhpStorm.
 * User: Vancior
 * Date: 2017/12/8
 * Time: 14:46
 */

use Vlite\Model;

class UserController extends BaseController
{
  public function signUp()
  {
    $model_user = new Model('user');

    $user_name = trim($_POST['user_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $this->output['status'] = 'failed';
    if (empty($user_name) or empty($email) or empty($password)) {
      $this->output['message'] = 'empty field';
      return;
    }

    if (!empty($model_user->where(['username' => $user_name])->select())) {
      $this->output['message'] = 'unavailable user name';
      return;
    }

    if (!empty($model_user->where(['email' => $email])->select())) {
      $this->output['message'] = 'unavailable email';
      return;
    }

    $encrypted = md5($password);
    $insert = [];
    $insert['username'] = $user_name;
    $insert['email'] = $email;
    $insert['password'] = $encrypted;
    if ($model_user->insert($insert)) {
      $this->output['status'] = 'success';
    } else {
      $this->output['message'] = 'database error';
    }
  }

  public function login()
  {
    $model_user = new Model('user');

    $user_email = trim($_POST['user_email']);
    $password = md5($_POST['password']);

    $user_info = $model_user->where(['email' => $user_email])->select();

    $this->output['status'] = 'failed';
    if (empty($user_info)) {
      $this->output['message'] = 'user not exist';
      return;
    }
    $user_info = $user_info[0];

    if ($password != $user_info->password) {
      $this->output['message'] = 'password not correct';
    } else {
      $this->output['status'] = 'success';
      session_start(['cookie_lifetime' => 86400]); // start session
      $_SESSION['user_info'] = $user_info;
      $this->autoLogin();
    }
  }

  public function logout()
  {
    session_destroy();
  }

  public function autoLogin()
  {
    if (!isset($_SESSION)) {
      $this->output = false;
      return;
    }

    $this->output['user_name'] = $_SESSION['user_info']->username;
    $this->output['icon'] = $_SESSION['user_info']->icon;
  }

  public function info($user_id)
  {
    $model_user = new Model('user');
    $this->output['status'] = 'failed';

    if (is_string($user_id))
      $user_id = intval($user_id);

    $user_info = $model_user->where(['id' => $user_id])->select();
    if (empty($user_info)) {
      $this->output['msg'] = 'user not exist';
      return;
    }

    $user_info = $user_info[0];
    $this->output['user_id'] = $user_info->id;
    $this->output['user_name'] = $user_info->username;
    $this->output['user_email'] = $user_info->email;
    $this->output['user_profile'] = $user_info->profile;
    $this->output['user_icon'] = $user_info->icon;
  }
}