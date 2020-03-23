<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    public function edit(Setting $setting)
    {
        $setting = Setting::first();

        if(!$setting) {
            $setting = Setting::createDefault();
        }

        return view('settings.edit', compact('setting'));
    }

    public function update(Request $request, Setting $setting)
    {
        $data = $request->validate([
            'currency_symbol' => 'nullable|string',
            'currency_decimal_separator' => 'nullable|string',
            'currency_thousands_separator' => 'nullable|string',
        ]);

        if($setting = Setting::firstOrFail()) {
            $setting->update($data);
        }

        return redirect()->route('settings.edit')
            ->withSuccess(trans('app.settings_saved_successfully'));
    }

    public function storeLogo(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
        ]);

        $setting = Setting::firstOrFail();
        $setting->addMedia($request->image)->toMediaCollection('logo');

        return response()->json(true);
    }

    public function removeLogo(Request $request)
    {
        $setting = Setting::firstOrFail();
        $setting->getFirstMedia('logo')->delete();

        return response()->json(true);
    }
}
