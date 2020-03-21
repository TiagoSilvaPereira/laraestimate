<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use Illuminate\Http\Request;
use App\Http\Requests\EstimateStoreRequest;
use App\Http\Requests\EstimateUpdateRequest;

class EstimateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $estimates = Estimate::search($search)->latest()->paginate(10);

        return view('estimates.index', compact('estimates', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('estimates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EstimateStoreRequest $request)
    {
        $data = $request->only('name', 'use_name_as_title');

        $estimate = Estimate::create($data);

        return redirect()
            ->route('estimates.edit', $estimate)
            ->withSuccess(trans('app.estimate_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Estimate  $estimate
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Estimate $estimate)
    {
        $canShareEmail = true;
        
        return view('estimates.show', compact('estimate', 'canShareEmail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Estimate  $estimate
     * @return \Illuminate\Http\Response
     */
    public function edit(Estimate $estimate)
    {
        return view('estimates.edit', compact('estimate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estimate  $estimate
     * @return \Illuminate\Http\Response
     */
    public function update(EstimateUpdateRequest $request, Estimate $estimate)
    {
        $data = $request->only('name', 'use_name_as_title', 'sections_positions');

        $estimate->update($data);
        $estimate->saveSectionsPositions($data['sections_positions']);

        return response()->json(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estimate  $estimate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Estimate $estimate)
    {
        $estimate->delete();
        
        return redirect()->route('estimates.index')
            ->withSuccess(trans('app.deleted_successfully'));
    }
}
