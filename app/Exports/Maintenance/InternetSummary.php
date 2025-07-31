<?php

namespace App\Exports\Maintenance;

class InternetSummary extends BaseServiceSummary
{
    protected string $issueTable = 'internet_issues';
    protected string $issueCometIdColumn = 'comet_id';
    protected string $actionTable = 'internet_actions';
    protected string $actionIdColumn = 'internet_action_id';
    protected string $titleName = 'Internet Summary';
}