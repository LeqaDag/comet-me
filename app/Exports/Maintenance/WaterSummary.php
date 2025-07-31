<?php

namespace App\Exports\Maintenance;

class WaterSummary extends BaseServiceSummary
{
    protected string $issueTable = 'water_issues';
    protected string $issueCometIdColumn = 'comet_id';
    protected string $actionTable = 'water_actions';
    protected string $actionIdColumn = 'water_action_id';
    protected string $titleName = 'Water Summary';
}
