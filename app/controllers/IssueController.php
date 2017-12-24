<?php
/**
 * Created by PhpStorm.
 * User: 44910_000
 * Date: 2017/12/14
 * Time: 17:42
 */

use Vlite\Model;

class IssueController extends BaseController
{
  public function issue($project_id)
  {
    $model_issue = new Model('issue');
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
    $this->output['project_id'] = $project_id;
    $this->output['title'] = $project_info->title;
    $this->output['label'] = $project_info->label;

    $issues = $model_issue->where(['project' => $project_id])->select();
    if (empty($issues))
      $this->output['empty'] = true;

    foreach ($issues as $item) {
      $item['issue_id'] = $item['id'];
      unset($item['id']);
    }

    $this->output['issue_list'] = $issues;
  }
}