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

    $user_name = trim($_POST['username']);
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

    $user_email = trim($_POST['email']);
    $password = md5($_POST['password']);

    $user_info = $model_user->where(['email' => $user_email])->select();

    $this->output['status'] = 'failed';
    if (empty($user_info)) {
      $this->output['message'] = 'user not exist';
      return;
    }
    $user_info = $user_info[0];
    $user_info->id = intval($user_info->id);

    if ($password != $user_info->password) {
      $this->output['message'] = 'password not correct';
    } else {
      $this->output['status'] = 'success';
      session_start(['cookie_lifetime' => 86400]); // start session
      $_SESSION['user_info'] = $user_info;
    }
  }

  public function logout()
  {
    session_start();
    session_unset();
    session_destroy();
  }

  public function autoLogin()
  {
    $model_project = new model('project');

    session_start(['cookie_lifetime' => 86400]);
    if (!isset($_SESSION['user_info'])) {
      $this->output = false;
      return;
    }

    $this->output['username'] = $_SESSION['user_info']->username;
    $this->output['email'] = $_SESSION['user_info']->email;
    $this->output['profile'] = $_SESSION['user_info']->profile;
    $this->output['icon'] = $_SESSION['user_info']->icon;

    $projects = $model_project->where(['owner' => $_SESSION['user_info']->id])->order('create_time desc')->select();
    foreach ($projects as $item) {
      $item->project_id = $item->id;
      unset($item->id);
    }
    $this->output['project_list'] = $projects;
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
    $this->output['username'] = $user_info->username;
    $this->output['email'] = $user_info->email;
    $this->output['profile'] = $user_info->profile;
    $this->output['icon'] = $user_info->icon;
    $this->output['status'] = 'success';
  }

  public function notification()
  {
    $model_issue = new Model('issue');
    $model_comment = new Model('issue_comment');
    $model_user = new Model('user');
    $model_project = new Model('project');

    session_start();
    if (!isset($_SESSION['user_info'])) {
      $this->output['status'] = 'failed';
      $this->output['message'] = 'not login';
      return;
    }

    $user_id = $_SESSION['user_info']->id;

    $issues = $model_issue->where(['owner' => $user_id])->order('create_time desc')->select();
    foreach ($issues as $issue) {
      $issue->issue_id = $issue->id;
      unset($issue->id);
      $user_name = $model_user->where(['id' => $issue->sponsor])->field('username')->select();
      $issue->sponsor_name = $user_name[0];
      $project_name = $model_project->where(['id' => $issue->project])->field('title')->select();
      $issue->project_name = $project_name[0];
    }
    $this->output['issue_list'] = $issues;

    $comments = $model_comment->where(['owner' => $user_id])->order('comment_time desc')->select();
    foreach ($comments as $comment) {
      $comment->comment_id = $comment->id;
      unset($comment->id);
      $user_name = $model_user->where(['id' => $comment->sponsor])->field('username')->select();
      $comment->sponsor_name = $user_name[0];
      $project_name = $model_project->where(['id' => $comment->project])->select();
      $comment->project_name = $project_name[0];
    }
    $this->output['comment_list'] = $comments;
  }
}
