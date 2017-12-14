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

    if (!isset($_SESSION)) {
      $this->output['message'] = 'not login';
      return;
    }

    $title = trim($_POST['project_title']);
    $description = trim($_POST['project_description']);
    $label = trim($_POST['project_label']);

    if (empty($title) || empty($description)) {
      $this->output['message'] = 'empty field';
      return;
    }

    $insert = [];
    $insert['title'] = $title;
    $insert['description'] = $description;
    $insert['label'] = $label;
    $insert['create_time'] = date('Y-m-d H:i:s');
    $insert['owner'] = $_SESSION['user_info']->id;
    $insert['file_name'] = '';
    $insert['version'] = '';
    $insert['stars'] = 0;

    if ($model_project->insert($insert))
      $this->output['status'] = 'success';
    else
      $this->output['message'] = 'db error';
  }
}