<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $getIdKaryawan = Karyawan::select('id')->first();
        $date = '2023-07-01';
        for($i = 0; $i < 30;$i++){
            if($i < 15){
                $check_in =  Carbon::parse($date.' 08:00:00');
                $check_out = Carbon::parse($date.' 16:00:00');
                $lateness = 0;
            } else if($i > 15 && $i <= 25){
                $check_in =  Carbon::parse($date.' 08:15:00');
                $check_out = Carbon::parse($date.' 16:00:00');
                $lateness = 15;
            } else if($i > 25 && $i <= 30){
                $check_in =  Carbon::parse($date.' 08:30:00');
                $check_out = Carbon::parse($date.' 16:00:00');
                $lateness = 30;
            }
            $insert = Absensi::create([
                'id_karyawan' => $getIdKaryawan->id,
                'check_in' => $check_in,
                'check_out' => $check_out,
                'lateness' => $lateness,
            ]);
        }
    }
}
