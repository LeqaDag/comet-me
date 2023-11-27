<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compound;

class CompoundController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->addMoreInputFieldsCompoundName) {
            foreach($request->addMoreInputFieldsCompoundName as $compoundName) {
                if($compoundName["subject"] != NULL) {
                    Compound::create([
                        'english_name' => $compoundName["subject"],
                        'community_id' => $request->community_id,
                    ]);
                }
            }
        }

        return redirect()->back()->with('message', 'New Compound Added Successfully!');
    }

}
