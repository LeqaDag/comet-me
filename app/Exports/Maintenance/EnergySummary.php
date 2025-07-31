<?php

namespace App\Exports\Maintenance;

class EnergySummary extends BaseServiceSummary
{
    protected string $issueTable = 'energy_issues';
    protected string $issueCometIdColumn = 'comet_id';
    protected string $actionTable = 'energy_actions';
    protected string $actionIdColumn = 'energy_action_id';
    protected string $titleName = 'Energy Summary';
}
