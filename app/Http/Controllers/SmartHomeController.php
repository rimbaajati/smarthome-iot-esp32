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
                'jemuran_ditarik' => 0
            ]);
        }

        return response()->json($status);
    }

    public function updateStatus(Request $request)
    {
        $status = SmartHome::create([
            'pintu_terbuka' => $request->input('pintu_terbuka'),
            'asap_terdeteksi' => $request->input('asap_terdeteksi'),
            'hujan_turun' => $request->input('hujan_turun'),
            'jemuran_ditarik' => $request->input('jemuran_ditarik'),
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan!',
            'data' => $status
        ], 201);
    }
}
