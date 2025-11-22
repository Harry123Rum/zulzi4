<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArmadaSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel dulu biar bersih (opsional, tapi aman)
        // DB::table('armada')->truncate(); 

        DB::table('armada')->insert([
            // KATEGORI RENTAL MOBIL (Ganti nama sesuai request)
            [
                'id_armada' => 1, 
                'no_plat' => 'B 1001 LRG', 
                'jenis_kendaraan' => 'Mobil Passanger', 
                'model' => 'Large', // Dulu Avanza
                'kapasitas' => '4 Seat', 
                'harga_sewa_per_hari' => 350000.00, 
                'status_ketersediaan' => 'Tersedia'
            ],
            [
                'id_armada' => 2, 
                'no_plat' => 'B 2002 XLR', 
                'jenis_kendaraan' => 'Mobil Passanger', 
                'model' => 'Extra Large', // Dulu Innova
                'kapasitas' => '6 Seat', 
                'harga_sewa_per_hari' => 500000.00, 
                'status_ketersediaan' => 'Tersedia'
            ],
            
            // KATEGORI ANGKUT BARANG/SAMPAH (Tetap atau sesuaikan)
            ['id_armada' => 3, 'no_plat' => 'B 9001 TRK', 'jenis_kendaraan' => 'Truk', 'model' => 'Engkel Box', 'kapasitas' => '2 Ton', 'harga_sewa_per_hari' => 700000.00, 'status_ketersediaan' => 'Tersedia'],
            ['id_armada' => 4, 'no_plat' => 'B 9002 PKP', 'jenis_kendaraan' => 'Pickup', 'model' => 'Pickup Bak', 'kapasitas' => '1.5 Ton', 'harga_sewa_per_hari' => 400000.00, 'status_ketersediaan' => 'Tersedia'],
            ['id_armada' => 5, 'no_plat' => 'B 9003 DMP', 'jenis_kendaraan' => 'Dump Truck', 'model' => 'Dump Truck', 'kapasitas' => '5 Ton', 'harga_sewa_per_hari' => 1200000.00, 'status_ketersediaan' => 'Tersedia'],
        ]);
    }
}