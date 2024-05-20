<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Option;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{


    public function index()
    {
        return view('settings.index');
    }

    public function approval()
    {
        return view('settings.approval');
    }

    public function saveOptions(Request $request)
    {
        $validated = $request->validate([
            'options' => 'required'
        ]);

        foreach($validated['options'] as $key => $settings)
        {

            if($this->optionSettingsBeforeSave($key)) {
                if($request->hasFile('options.app_logo'))
                {
                    $file = $request->file('options.app_logo');
                    $hashname = $file->hashName();
                    $path = $request->file('options.app_logo')->storeAs("settings", $hashname, "public_upload");
                    Option::updateByKey($key, $path);
                }
            }
            else {
                Option::updateByKey($key, $settings);
            }
            
        }
        
        
        return back()
            ->with('status', 'success')
            ->with('messsage', 'Pengaturan berhasil disimpan');

    }

    public function optionSettingsBeforeSave($key)
    {
        $configs = [
            'app_logo',
        ];

        if(in_array($key, $configs)){
            return true;
        }

        return false;
    }

}
