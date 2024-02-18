<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compound;
use App\Models\GridCommunityCompound;

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

                    $compound = new Compound();
                    $compound->english_name = $compoundName["subject"];
                    $compound->community_id = $request->community_id;
                    $compound->save();

                    $gridCompound = new GridCommunityCompound();
                    $gridCompound->compound_id = $compound->id;
                    $gridCompound->save();
                }
            }
        }

        return redirect()->back()->with('message', 'New Compound Added Successfully!');
    }

}
