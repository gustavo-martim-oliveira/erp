<?php

namespace App\Http\Controllers\Central\Pages\Settings;

use App\Http\Controllers\Controller;
use App\Jobs\Central\ExportDatabaseJob;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('central.pages.system.index', [
            'settings' => SystemSetting::all()->keyBy('key'),
            'page' => 'system',
            'title' => 'Configurações do Sistema'
        ]);
    }

    public function update(Request $request)
    {
        foreach ($request->all() as $key => $value) {

            if ($key === '_token') continue;

            SystemSetting::set($key, $value);
        }

        return back()->with('success', 'Configurações atualizadas!');
    }

    public function exportDatabase()
    {
        ExportDatabaseJob::dispatch();

        return back()->with('success', 'Backup iniciado!');
    }

}
