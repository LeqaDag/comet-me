<?php

namespace App\Exports\Maintenance;

class RefrigeratorSummary extends BaseServiceSummary
{
    protected string $issueTable = 'refrigerator_issues';
    protected string $issueCometIdColumn = 'comet_id';
    protected string $actionTable = 'refrigerator_actions';
    protected string $actionIdColumn = 'refrigerator_action_id';
    protected string $titleName = 'Refrigerator Summary';
}
