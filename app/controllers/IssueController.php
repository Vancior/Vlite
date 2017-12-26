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
      $item->issue_id = $item->id;
      unset($item->id);
    }

    $this->output['issue_list'] = $issues;
  }

  public function info($issue_id)
  {
    $model_issue = new Model('issue');
    $model_comment = new Model('issue_comment');
    $model_user = new Model('user');
    $this->output['status'] = 'success';

    if (is_string($issue_id))
      $issue_id = intval($issue_id);

    $issue_info = $model_issue->where(['id' => $issue_id])->select();

    if (empty($issue_info)) {
      $this->output['status'] = 'failed';
      $this->output['message'] = 'invalid issue id';
      return;
    }

    $issue_info = $issue_info[0];
    $issue_info->issue_id = $issue_info->id;
    unset($issue_info->id);
    $this->output = $issue_info;

    $comments = $model_comment->where(['issue' => $issue_id])->select();
    if (empty($comments)) {
      $this->output['empty'] = true;
      return;
    }

    foreach ($comments as $item) {
      $item->comment_id = $item->id;
      unset($item->id);
      $user_name = $model_user->where(['id' => $item->sponsor])->field('username')->select();
      $item->username = $user_name[0];
    }

    $this->output['comment_list'] = $comments;
  }

  public function create($project_id)
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

    session_start();
    if (!isset($_SESSION['user_info'])) {
      $this->output['status'] = 'failed';
      $this->output['message'] = 'not login';
      return;
    }

    $insert = [];
    $insert['title'] = $_POST['title'];
    $insert['description'] = $_POST['description'];
    $create_time = date('Y-m-d H:i:s');
    $insert['create_time'] = $create_time;
    $insert['label'] = $_POST['label'];
    $insert['sponsor'] = $_SESSION['user_info']->id;
    $insert['project'] = $project_info->id;
    $insert['owner'] = $project_info->owner;
    $insert['state'] = 1;
    $insert['is_read'] = 0;

    if ($model_issue->insert($insert)) {
      $issue_info = $model_issue->where(['title' => $_POST['title'], 'sponsor' => $_SESSION['user_info']->id, 'create_time' => $create_time])->select();
      $this->output['$issue_id'] = $issue_info[0]->id;
    } else {
      $this->output['status'] = 'failed';
      $this->output['message'] = 'db error';
    }
  }
}