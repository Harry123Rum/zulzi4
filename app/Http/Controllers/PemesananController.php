<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PemesananController extends Controller
{
    public function store(Request $request)
    {
        // 1. Tentukan Validasi Dasar
        $rules = [
            'layanan' => 'required|in:rental,barang,sampah',
            'tgl_mulai' => 'required|date',
            'lokasi_jemput' => 'required|string',
            // FIX: id_armada WAJIB diisi (required) karena database menolak null
            'id_armada' => 'required|integer', 
            
            // Validasi lainnya
            'lama_rental' => 'nullable|integer',
            'foto_barang' => 'nullable|file|image|max:10240',
            'foto_sampah' => 'nullable|file|image|max:10240',
        ];

        // Validasi khusus berdasarkan layanan
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2. Tentukan ID Layanan
        $layananId = match($request->layanan) {
            'rental' => 1,
            'barang' => 2,
            'sampah' => 3,
            default => 1,
        };

        // 3. Logika Deskripsi (Gabung data sampah)
        $deskripsi = $request->deskripsi_barang;
        if ($request->layanan === 'sampah') {
            $jenis = $request->jenis_sampah ?? '-';
            $volume = $request->perkiraan_volume ?? '-';
            $deskripsi = "Jenis Sampah: $jenis, Volume: $volume";
        }

        // 4. Siapkan Data
        $data = [
            'id_pengguna' => auth()->id() ?? 1,
            'id_layanan' => $layananId,
            'tgl_pesan' => Carbon::now(),
            'tgl_mulai' => $request->tgl_mulai,
            'lokasi_jemput' => $request->lokasi_jemput,
            'lokasi_tujuan' => $request->lokasi_tujuan ?? $request->lokasi_jemput, // Default ke lokasi_jemput jika tidak ada
            'status_pemesanan' => 'pending_approval',
            'total_biaya' => 0,
            
            // Wajib ada isinya sekarang
            'id_armada' => $request->id_armada, 
            
            'tgl_selesai' => null,
            'deskripsi_barang' => $deskripsi,
            'est_berat_ton' => $request->est_berat_ton ?? null,
            'jumlah_orang' => null,
            'lama_rental' => $request->lama_rental ?? null,
        ];

        // 5. Handle Upload Foto
        $file = $request->file('foto_barang') ?? $request->file('foto_sampah');
        if ($file) {
            $path = $file->store('public/uploads/pemesanan');
            $data['foto_barang'] = Storage::url($path);
        } else {
            $data['foto_barang'] = null;
        }

        // 6. Simpan
        try {
            $pemesanan = Pemesanan::create($data);
            return response()->json([
                'message' => 'Pesanan berhasil dibuat!',
                'data' => $pemesanan
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Pemesanan Error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            return response()->json([
                'message' => 'Gagal menyimpan pesanan',
                'error' => $e->getMessage(),
                'debug' => config('app.debug') ? $data : null
            ], 500);
        }
    }
}