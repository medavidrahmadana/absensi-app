<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kehadiran;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class KehadiranController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit'
        ]);

        return Kehadiran::create($data);
    }

    public function report($pegawaiId, $year, $month)
    {
        $start = Carbon::create($year, $month)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $totalHariKerja = collect(CarbonPeriod::create($start, $end))
            ->filter(fn($date) => $date->dayOfWeek !== Carbon::SUNDAY)
            ->count();

        $hadir = Kehadiran::where('pegawai_id', $pegawaiId)
            ->whereBetween('tanggal', [$start, $end])
            ->where('status', 'hadir')
            ->count();

        $izin = Kehadiran::where('pegawai_id', $pegawaiId)
            ->whereBetween('tanggal', [$start, $end])
            ->where('status', 'izin')
            ->count();

        $sakit = Kehadiran::where('pegawai_id', $pegawaiId)
            ->whereBetween('tanggal', [$start, $end])
            ->where('status', 'sakit')
            ->count();

        $alpha = $totalHariKerja - ($hadir + $izin + $sakit);

        return response()->json([
            'total_hari_kerja' => $totalHariKerja,
            'hadir' => $hadir,
            'izin' => $izin,
            'sakit' => $sakit,
            'alpha' => $alpha
        ]);
    }
}
