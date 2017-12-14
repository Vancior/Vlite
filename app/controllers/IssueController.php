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
        $this->output['status'] = 'success';

        if (is_string($project_id))
            $project_id = intval($project_id);

        $issues = $model_issue->where(['project' => $project_id])->select();
        if (empty($issues))
            $this->output['empty'] = true;

        $this->output = $issues;
    }
}