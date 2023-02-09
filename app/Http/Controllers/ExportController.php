<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * Build DataTable class.
     *
     * @param Controller\ExportDataTable $dataTable
     * @return 
     */
    public function index(ExportDataTable $dataTable)
    {
        
        return $dataTable->render('export');
    }
}
