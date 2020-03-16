<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Estimate;
use Illuminate\Http\Request;
use App\Http\Requests\SectionStoreRequest;
use App\Http\Requests\SectionUpdateRequest;

class SectionController extends Controller
{
    
    public function index(Request $request, Estimate $estimate)
    {
        return response()->json($estimate->sections);
    }

    public function store(SectionStoreRequest $request, Estimate $estimate)
    {
        $data = $request->only(['text', 'type']);

        $section = $estimate->sections()->create($data);

        $items = $request->get('items', []);
        $section->saveItems($items);

        return response()->json($section);
    }

    public function update(SectionUpdateRequest $request, Estimate $estimate, Section $section)
    {
        $data = $request->only(['text']);

        $section->update($data);

        $items = $request->get('items', []);
        $section->saveItems($items);

        return response()->json(true);
    }

    public function destroy(Request $request, Estimate $estimate, Section $section)
    {
        $section->delete();

        return response()->json(true);
    }

}
