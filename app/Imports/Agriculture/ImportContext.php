<?php

namespace App\Imports\Agriculture;

use Illuminate\Support\Facades\DB;
use App\Models\AgricultureImportContext;

class ImportContext
{
    public static function addHolder($index, $holderId)
    {
        if (!$index || !$holderId) return;

        $importContext = new AgricultureImportContext();
        $importContext->excel_index = $index;
        $importContext->agriculture_holder_id = $holderId;
        $importContext->save();
    }

    public static function getHolderId($index)
    {
        if (!$index) return null;

        return DB::table('agriculture_import_contexts')
            ->where('excel_index', $index)
            ->value('agriculture_holder_id');
    }
}
