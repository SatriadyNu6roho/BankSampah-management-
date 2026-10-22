<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Report;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{
    // API 1: Login & Filterisasi Role
    public function login(Request $request)
    {
        $rawUsername = $request->query('username') ?? $request->input('username');
        $rawPassword = $request->query('password') ?? $request->input('password');

        $username = trim((string) $rawUsername);
        $password = trim((string) $rawPassword);

        if (empty($username) || empty($password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Username dan Password wajib diisi!'
            ], 200);
        }

        $user = User::whereRaw('LOWER(username) = ?', [strtolower($username)])->first();

        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Username tidak terdaftar!'
            ], 200);
        }

        $isPasswordValid = false;
        if (Hash::check($password, $user->password) || $password === $user->password) {
            $isPasswordValid = true;
        }

        if (!$isPasswordValid) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Username atau Password salah!'
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'role'   => strtolower(trim($user->role)),
            'id'     => (string) $user->id,
            'message'=> 'Login Berhasil!'
        ], 200);
    }

    // API 2: Upload Bukti Sampah (Kompresi Gambar Otomatis & Fix Bug Syntax)
    public function uploadSampah(Request $request)
    {
        $userId = $request->query('user_id') ?? $request->input('user_id');
        $jenisSampah = $request->query('jenis_sampah') ?? $request->input('jenis_sampah');

        if (empty($userId)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal: ID User tidak ditemukan dari aplikasi mobile!'
            ], 400);
        }

        $imageName = 'sampah_' . time() . '_' . uniqid() . '.jpg';
        $destinationPath = storage_path('app/public/uploads/sampah');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $uploadedFile = $request->file('file') ?? $request->file('foto');

        if ($uploadedFile) {
            $sourcePath = $uploadedFile->getRealPath();
        } else {
            // Ambil dari raw binary kiriman MIT App Inventor
            $tempPath = tempnam(sys_get_temp_dir(), 'upload_');
            file_put_contents($tempPath, $request->getContent());
            $sourcePath = $tempPath; // PERBAIKAN: Penambahan tanda $ pada $tempPath
        }

        if (file_exists($sourcePath) && filesize($sourcePath) > 0) {
            $imgData = @file_get_contents($sourcePath);
            $srcImage = @imagecreatefromstring($imgData);

            if ($srcImage !== false) {
                $width = imagesx($srcImage);
                $height = imagesy($srcImage);

                $maxDim = 1000;
                if ($width > $maxDim || $height > $maxDim) {
                    $ratio = min($maxDim / $width, $maxDim / $height);
                    $newWidth = round($width * $ratio);
                    $newHeight = round($height * $ratio);
                } else {
                    $newWidth = $width;
                    $newHeight = $height;
                }

                $dstImage = imagecreatetruecolor($newWidth, $newHeight);
                imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                imagejpeg($dstImage, $destinationPath . '/' . $imageName, 70);

                imagedestroy($srcImage);
                imagedestroy($dstImage);
            } else {
                if ($uploadedFile) {
                    $uploadedFile->storeAs('uploads/sampah', $imageName, 'public');
                } else {
                    Storage::disk('public')->put('uploads/sampah/' . $imageName, $request->getContent());
                }
            }
        }

        if (!$uploadedFile && isset($tempPath) && file_exists($tempPath)) {
            @unlink($tempPath);
        }

        $report = Report::create([
            'user_id'      => $userId,
            'jenis_sampah' => $jenisSampah,
            'foto'         => $imageName,
            'status'       => 'Menunggu',
            'bobot_kg'     => 0,
            'poin'         => 0,
            'saldo_rp'     => 0
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Bukti sampah berhasil diunggah! Menunggu verifikasi admin.',
            'data'    => $report
        ], 200); // PERBAIKAN: Menggunakan HTTP Status 200 agar dibaca sukses oleh App Inventor
    }

    // API 3: Ringkasan Dompet, Poin, Saldo, & Status Setoran Warga
    public function getUserWallet(Request $request)
    {
        $userId = $request->query('user_id') ?? $request->input('user_id');

        if (empty($userId)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal: User ID dibutuhkan!'
            ], 400);
        }

        $reports = Report::where('user_id', $userId)->get();

        $totalPoin = $reports->where('status', 'Disetujui')->sum('poin');
        $totalSaldo = $reports->where('status', 'Disetujui')->sum('saldo_rp');
        $totalKg = $reports->where('status', 'Disetujui')->sum('bobot_kg');

        $riwayat = $reports->sortByDesc('created_at')->values()->map(function($item) {
            return [
                'id'           => $item->id,
                'jenis_sampah' => $item->jenis_sampah,
                'bobot_kg'     => (float)$item->bobot_kg,
                'poin'         => (int)$item->poin,
                'saldo_rp'     => 'Rp ' . number_format($item->saldo_rp, 0, ',', '.'),
                'status'       => $item->status,
                'waktu'        => $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-'
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => [
                'total_poin'     => (int)$totalPoin,
                'total_saldo_rp' => 'Rp ' . number_format($totalSaldo, 0, ',', '.'),
                'total_kg'       => (float)$totalKg,
                'riwayat'        => $riwayat
            ]
        ], 200);
    }
}