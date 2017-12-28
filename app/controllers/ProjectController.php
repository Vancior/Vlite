<?php
/**
 * Created by PhpStorm.
 * User: Vancior
 * Date: 2017/12/14
 * Time: 10:31
 */

use Vlite\Model;

class ProjectController extends BaseController
{
  public function create()
  {
    $model_project = new Model('project');
    $this->output['status'] = 'failed';

    session_start();
    if (!isset($_SESSION['user_info'])) {
      $this->output['message'] = 'not login';
      return;
    }

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $label = trim($_POST['label']);

    if (empty($title) || empty($description)) {
      $this->output['message'] = 'empty field';
      return;
    }

    $insert = [];
    $insert['title'] = $title;
    $insert['description'] = $description;
    $insert['label'] = $label;
    $create_time = date('Y-m-d H:i:s');
    $insert['create_time'] = $create_time;
    $insert['owner'] = $_SESSION['user_info']->id;
    $insert['file_name'] = '';
    $insert['version'] = '';
    $insert['stars'] = 0;

    if ($model_project->insert($insert)) {
      $this->output['status'] = 'success';
      $project_info = $model_project->where(['title' => $title, 'owner' => $_SESSION['user_info']->id, 'create_time' => $create_time])->select();
      $this->output['project_id'] = $project_info[0]->id;
    } else
      $this->output['message'] = 'db error';
  }

  public function download($project_id)
  {
    $model_project = new Model('project');
    $this->output['status'] = 'success';

    if (is_string($project_id))
      $project_id = intval($project_id);

    $project_info = $model_project->where(['id' => $project_id])->select();
    if (empty($project_info)) {
      $this->output['status'] = 'failed';
      $this->output['message'] = 'invalid project id';
      return;
    }
    $project_info = $project_info[0];

    if (empty($project_info->file_name)) {
      header('HTTP/1.1 404 Not Found');
      $this->exit = true;
      exit();
    }

    $path = BASE_PATH . '/upload/' . $project_info->id . '-' . $project_info->file_name;
    if (!file_exists($path)) {
      header('HTTP/1.1 404 Not Found');
      $this->exit = true;
      exit();
    } else {
      $this->exit = true;
      $file = fopen($path, "r");
      header("HTTP/1.1 200");
      header("Content-type: application/octet-stream");
      header("Accept-Ranges: bytes");
      header("Accept-Length: " . filesize($path));
      header("Content-Disposition: attachment; filename=" . $project_info->file_name);
      echo fread($file, filesize($path));
      fclose($file);
      exit();
    }
  }

  public function search()
  {
    $model_project = new Model('project');
    $model_user = new Model('user');

    if (isset($_GET['keyword']))
      $keyword = trim($_GET['keyword']);
    else
      $keyword = '';
    if (isset($_GET['page']))
      $page = intval($_GET['page']);
    else
      $page = 1;
/*
    if (isset($_GET['label']))
      $label = trim($_GET['label']);
    else
      $label = '';
*/

    $projects = $model_project->join('(select username, id as user_id from user) temp')->on('project.owner = temp.user_id')->where("(title like '%$keyword%') OR (label like '%$keyword%') or (username like '%$keyword%')")->page($page)->order('stars desc, create_time desc')->select();
    foreach ($projects as $item) {
      $item->project_id = $item->id;
      unset($item->id);
      $user_info = $model_user->where(['id' => $item->owner])->select();
      $item->username = $user_info[0]->username;
    }

    $this->output = $projects;
  }

  public function upload($project_id)
  {
    $model_project = new Model('project');
    $this->output['status'] = 'failed';

    if (is_string($project_id))
      $project_id = intval($project_id);

    if (!isset($_POST['version'])) {
      $this->output['message'] = 'no version';
      return;
    }
    $version = trim($_POST['version']);

    session_start();
    if (!isset($_SESSION['user_info'])) {
      $this->output['message'] = 'not login';
      return;
    }

    $project_info = $model_project->where(['id' => $project_id])->select();
    if (empty($project_info)) {
      $this->output['message'] = 'invalid project id';
      return;
    }

    $project_info = $project_info[0];
    if ($_SESSION['user_info']->id != $project_info->owner) {
      $this->output['message'] = 'not owner';
      return;
    }

    if (!isset($_FILES['file'])) {
      $this->output['message'] = 'no file';
      return;
    }

    if ($_FILES['file']['error'] > 0) {
      $this->output['message'] = 'get error' . $_FILES['file']['error'];
    } else {
      $this->output['status'] = 'success';
      $_FILES['file']['name'] = str_replace('/', '-', $_FILES['file']['name']);
      $this->output['file_name'] = $_FILES['file']['name'];
      $this->output['file_type'] = $_FILES['file']['type'];

      move_uploaded_file($_FILES['file']['tmp_name'],
          BASE_PATH . '/upload/' . $project_id . '-' . $_FILES['file']['name']);

      $update = [];
      $update['file_name'] = $_FILES['file']['name'];
      $update['version'] = $version;

      if (!$model_project->where(['id' => $project_id])->update($update)) {
        $this->output['status'] = 'failed';
        $this->output['message'] = 'db error';
        $this->output['update'] = $update;
      }
    }
  }
}
