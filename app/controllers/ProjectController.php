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
      exit();
    }

    $path = BASE_PATH . '/upload/' . $project_info->file_name;
    if (!file_exists($path)) {
      header('HTTP/1.1 404 Not Found');
      exit();
    } else {
      $file = fopen($path, "r");
      Header("Content-type: application/octet-stream");
      Header("Accept-Ranges: bytes");
      Header("Accept-Length: " . filesize($path));
      Header("Content-Disposition: attachment; filename=" . $path);
      echo fread($file, filesize($path));
      fclose($file);
      exit ();
    }
  }
}