<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SmartHome;

class SmartHomeController extends Controller
{
    public function getStatus()
    {
        $status = SmartHome::latest()->first();

        if (!$status) {
            return response()->json([
                'pintu_terbuka' => 0,
                'asap_terdeteksi' => 0,
                'hujan_turun' => 0,
                'jemuran_ditarik' => 0,
                'suhu' => 0,
                'kelembapan' => 0
            ]);
        }

        return response()->json($status);
    }

    public function updateStatus(Request $request)
    {
        $status = SmartHome::create([
            'pintu_terbuka' => $request->input('pintu_terbuka', 0),
            'asap_terdeteksi' => $request->input('asap_terdeteksi', 0),
            'hujan_turun' => $request->input('hujan_turun', 0),
            'jemuran_ditarik' => $request->input('jemuran_ditarik', 0),
            'suhu' => $request->input('suhu', 0),
            'kelembapan' => $request->input('kelembapan', 0),
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan!',
            'data' => $status
        ], 201);
    }

    public function getSettings()
    {
        $settings = \App\Models\SystemSetting::first();
        if (!$settings) {
            $settings = \App\Models\SystemSetting::create([
                'pintu_enabled' => true,
                'asap_enabled' => true,
                'hujan_enabled' => true,
                'jemuran_enabled' => true,
            ]);
        }
        return response()->json($settings);
    }

    public function updateSettings(Request $request)
    {
        $settings = \App\Models\SystemSetting::first();
        if (!$settings) {
            $settings = new \App\Models\SystemSetting();
        }

        if ($request->has('pintu_enabled')) $settings->pintu_enabled = $request->boolean('pintu_enabled');
        if ($request->has('asap_enabled')) $settings->asap_enabled = $request->boolean('asap_enabled');
        if ($request->has('hujan_enabled')) $settings->hujan_enabled = $request->boolean('hujan_enabled');
        if ($request->has('jemuran_enabled')) $settings->jemuran_enabled = $request->boolean('jemuran_enabled');
        
        $settings->save();

        return response()->json([
            'message' => 'Pengaturan berhasil diperbarui!',
            'data' => $settings
        ]);
    }
}
