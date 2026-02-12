<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kehadiran;
use App\Models\Pegawai;
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
        $pegawai = Pegawai::findOrFail($pegawaiId);

        $startBulan = Carbon::create($year, $month)->startOfMonth();
        $endBulan   = $startBulan->copy()->endOfMonth();

        $today = Carbon::today();

        // Total hari kerja full bulan (tanpa Minggu)
        $totalHariKerjaBulan = collect(
            CarbonPeriod::create($startBulan, $endBulan)
        )->filter(fn($date) => $date->dayOfWeek !== Carbon::SUNDAY)
            ->count();

        /**
         * Kalau bulan di masa depan
         * → aktif = 0 tapi total bulan tetap tampil
         */
        if ($startBulan->greaterThan($today)) {
            return response()->json([
                'total_hari_kerja_bulan' => $totalHariKerjaBulan,
                'hari_kerja_aktif' => 0,
                'hadir' => 0,
                'izin' => 0,
                'sakit' => 0,
                'alpha' => 0
            ]);
        }

        // Kalau bulan sekarang → aktif sampai hari ini
        if ($today->between($startBulan, $endBulan)) {
            $endAktif = $today;
        } else {
            $endAktif = $endBulan;
        }

        $tanggalMasuk = Carbon::parse($pegawai->tanggal_masuk);

        /**
         * Kalau belum masuk kerja di periode ini
         */
        if ($tanggalMasuk->greaterThan($endAktif)) {
            return response()->json([
                'total_hari_kerja_bulan' => 0,
                'hari_kerja_aktif' => 0,
                'hadir' => 0,
                'izin' => 0,
                'sakit' => 0,
                'alpha' => 0
            ]);
        }

        // Total hari kerja full bulan (tanpa Minggu)
        $totalHariKerjaBulan = collect(
            CarbonPeriod::create($startBulan, $endBulan)
        )->filter(fn($date) => $date->dayOfWeek !== Carbon::SUNDAY)
            ->count();

        // Tentukan mulai aktif
        $startAktif = $tanggalMasuk->greaterThan($startBulan)
            ? $tanggalMasuk
            : $startBulan;

        // Hitung hari kerja aktif
        $hariKerjaAktif = collect(
            CarbonPeriod::create($startAktif, $endAktif)
        )->filter(fn($date) => $date->dayOfWeek !== Carbon::SUNDAY)
            ->count();

        $hadir = Kehadiran::where('pegawai_id', $pegawaiId)
            ->whereBetween('tanggal', [$startAktif, $endAktif])
            ->where('status', 'hadir')
            ->count();

        $izin = Kehadiran::where('pegawai_id', $pegawaiId)
            ->whereBetween('tanggal', [$startAktif, $endAktif])
            ->where('status', 'izin')
            ->count();

        $sakit = Kehadiran::where('pegawai_id', $pegawaiId)
            ->whereBetween('tanggal', [$startAktif, $endAktif])
            ->where('status', 'sakit')
            ->count();

        $alpha = max(0, $hariKerjaAktif - ($hadir + $izin + $sakit));

        return response()->json([
            'total_hari_kerja_bulan' => $totalHariKerjaBulan,
            'hari_kerja_aktif' => $hariKerjaAktif,
            'hadir' => $hadir,
            'izin' => $izin,
            'sakit' => $sakit,
            'alpha' => $alpha
        ]);
    }
}
