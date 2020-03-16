<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Estimate;
use Illuminate\Http\Request;
use App\Http\Requests\SectionStoreRequest;

class SectionController extends Controller
{
    
    public function index(Request $request, Estimate $estimate)
    {
        return response()->json($estimate->sections);
    }

    public function store(SectionStoreRequest $request, Estimate $estimate)
    {
        $data = $request->only(['text']);

        $section = $estimate->sections()->create($data);

        return response()->json($section);
    }

    public function update(SectionStoreRequest $request, Estimate $estimate, Section $section)
    {
        $data = $request->only(['text']);

        $section->update($data);

        return response()->json(true);
    }

    public function destroy(Request $request, Estimate $estimate, Section $section)
    {
        $section->delete();
        
        return response()->json(true);
    }

}
