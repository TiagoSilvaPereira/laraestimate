<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Estimate;
use Illuminate\Http\Request;
use App\Http\Requests\EstimateStoreRequest;
use App\Http\Requests\EstimateUpdateRequest;

class EstimateController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $estimates = Estimate::search($search)->latest()->paginate(10);

        return view('estimates.index', compact('estimates', 'search'));
    }

    public function create()
    {
        $setting = Setting::first();
        return view('estimates.create', compact('setting'));
    }

    public function store(EstimateStoreRequest $request)
    {
        $data = $request->all();

        $estimate = Estimate::create($data);

        return redirect()
            ->route('estimates.edit', $estimate)
            ->withSuccess(trans('app.estimate_created_successfully'));
    }

    public function show(Request $request, Estimate $estimate)
    {
        $canShareEmail = true;
        
        return view('estimates.show', compact('estimate', 'canShareEmail'));
    }

    public function edit(Estimate $estimate)
    {
        return view('estimates.edit', compact('estimate'));
    }

    public function update(EstimateUpdateRequest $request, Estimate $estimate)
    {
        $data = $request->all();

        $estimate->update($data);
        $estimate->saveSectionsPositions($data['sections_positions']);

        return response()->json(true);
    }

    public function destroy(Estimate $estimate)
    {
        $estimate->delete();
        
        return redirect()->route('estimates.index')
            ->withSuccess(trans('app.deleted_successfully'));
    }

    public function duplicate(Request $request, Estimate $estimate)
    {
        $duplicated = $estimate->duplicate();
        
        return redirect()->route('estimates.edit', $duplicated);
    }
}
