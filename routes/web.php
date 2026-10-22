<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Report;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/dashboard', function () {
    $reports = Report::with('user')->latest()->get();
    $users = User::latest()->get();

    $totalLaporan = $reports->count();
    $totalOrganik = $reports->where('jenis_sampah', 'Organik')->count();
    $totalNonOrganik = $reports->where('jenis_sampah', 'Non-Organik')->count();

    $dailyStats = [
        'Minggu' => $reports->filter(function($r) { return $r->created_at && $r->created_at->dayOfWeek === 0; })->count(),
        'Senin'  => $reports->filter(function($r) { return $r->created_at && $r->created_at->dayOfWeek === 1; })->count(),
        'Selasa' => $reports->filter(function($r) { return $r->created_at && $r->created_at->dayOfWeek === 2; })->count(),
        'Rabu'   => $reports->filter(function($r) { return $r->created_at && $r->created_at->dayOfWeek === 3; })->count(),
        'Kamis'  => $reports->filter(function($r) { return $r->created_at && $r->created_at->dayOfWeek === 4; })->count(),
        'Jumat'  => $reports->filter(function($r) { return $r->created_at && $r->created_at->dayOfWeek === 5; })->count(),
        'Sabtu'  => $reports->filter(function($r) { return $r->created_at && $r->created_at->dayOfWeek === 6; })->count(),
    ];

    $hariTeramai = 'Belum Ada Data';
    $maxSetor = 0;
    foreach ($dailyStats as $hari => $jumlah) {
        if ($jumlah > $maxSetor) {
            $maxSetor = $jumlah;
            $hariTeramai = $hari;
        }
    }

    return view('admin.dashboard', compact(
        'reports', 'users', 'totalLaporan', 'totalOrganik', 'totalNonOrganik',
        'dailyStats', 'hariTeramai', 'maxSetor'
    ));
});

// ROUTE BARU: VERIFIKASI SETORAN & INPUT BOBOT SAMPAH
Route::post('/admin/reports/verify/{id}', function (Request $request, $id) {
    $report = Report::findOrFail($id);

    $bobotKg = (float) $request->input('bobot_kg', 0);
    $status = $request->input('status', 'Disetujui');

    if ($status === 'Disetujui') {
        // RUMUS KALKULASI POIN & SALDO RUPIAH:
        // Organik: 1 kg = 10 Poin / Rp 1.000
        // Non-Organik: 1 kg = 30 Poin / Rp 3.000
        if ($report->jenis_sampah === 'Organik') {
            $poin = $bobotKg * 10;
            $saldoRp = $bobotKg * 1000;
        } else {
            $poin = $bobotKg * 30;
            $saldoRp = $bobotKg * 3000;
        }

        $report->update([
            'bobot_kg' => $bobotKg,
            'poin'     => $poin,
            'saldo_rp' => $saldoRp,
            'status'   => 'Disetujui'
        ]);
    } else {
        $report->update([
            'status'   => 'Ditolak',
            'bobot_kg' => 0,
            'poin'     => 0,
            'saldo_rp' => 0
        ]);
    }

    return redirect('/admin/dashboard')->with('success', 'Setoran sampah berhasil diverifikasi!');
});

Route::post('/admin/users/store', function (Request $request) {
    $request->validate([
        'username' => 'required|unique:users,username',
        'name'     => 'required',
        'password' => 'required',
        'role'     => 'required'
    ]);

    User::create([
        'username' => strtolower(trim($request->username)),
        'name'     => trim($request->name),
        'password' => Hash::make($request->password),
        'role'     => strtolower(trim($request->role)),
    ]);

    return redirect('/admin/dashboard')->with('success', 'User warga berhasil ditambahkan!');
});

Route::post('/admin/users/update/{id}', function (Request $request, $id) {
    $user = User::findOrFail($id);
    $user->name = trim($request->name);
    $user->role = strtolower(trim($request->role));

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();
    return redirect('/admin/dashboard')->with('success', 'Data user / password berhasil diperbarui!');
});

Route::delete('/admin/users/delete/{id}', function ($id) {
    $user = User::findOrFail($id);
    $user->delete();
    return redirect('/admin/dashboard')->with('success', 'User berhasil dihapus!');
});

Route::delete('/admin/reports/delete/{id}', function ($id) {
    $report = Report::findOrFail($id);
    if ($report->foto && Storage::disk('public')->exists('uploads/sampah/' . $report->foto)) {
        Storage::disk('public')->delete('uploads/sampah/' . $report->foto);
    }
    $report->delete();
    return redirect('/admin/dashboard')->with('success', 'Laporan sampah berhasil dihapus!');
});

Route::get('/admin/reports/export-spreadsheet', function () {
    $reports = Report::with('user')->latest()->get();
    $namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $fileName = 'Laporan_Pengumpulan_Sampah_' . date('Y-m-d_H-i') . '.csv';

    $headers = [
        "Content-type"        => "text/csv; charset=UTF-8",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    $callback = function() use ($reports, $namaHari) {
        $file = fopen('php://output', 'w');
        fputs($file, "\xEF\xBB\xBF");
        fputs($file, "sep=;\n");

        fputcsv($file, ['No', 'Nama Warga', 'Jenis Sampah', 'Status', 'Bobot (Kg)', 'Poin', 'Saldo Rp', 'Hari Setor', 'Tanggal & Waktu'], ';');

        foreach ($reports as $index => $report) {
            $hariText = $report->created_at ? $namaHari[$report->created_at->dayOfWeek] : '-';
            fputcsv($file, [
                $index + 1,
                $report->user->name ?? 'Warga',
                $report->jenis_sampah,
                $report->status,
                $report->bobot_kg . ' Kg',
                $report->poin,
                'Rp ' . number_format($report->saldo_rp, 0, ',', '.'),
                $hariText,
                $report->created_at ? $report->created_at->format('d/m/Y H:i') . ' WIB' : '-'
            ], ';');
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
});