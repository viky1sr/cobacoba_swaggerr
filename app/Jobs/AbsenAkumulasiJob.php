<?php

namespace App\Jobs;

use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class AbsenAkumulasiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Absensi $dataAbsensi
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = $this->dataAbsensi;
        if(!empty($data->check_in)){
            $masuk = strtotime('07:00:00');
            $time = strtotime(Carbon::parse($data->check_in)->format("H:i:s"));
            $lateness = ceil(($time - $masuk) / 60);
            $deduction = 0;
            if($lateness >= 15){
                $deduction = 5000;
            } else if($lateness >= 30){
                $deduction = 10000;
            }
            $key = 'id_karyawan:'.$data->id_karyawan.'_'.Carbon::parse($data->check_in)->format('Y-m');
            $getDataCache = Cache::get($key);
            $dataCache = [
                'date' => Carbon::parse($data->check_in)->format('Y-m-d'),
                'deduction' => $deduction,
                'lateness' => $lateness
            ];
            $mergeCache = !is_null($getDataCache) ? array_merge($getDataCache,[$dataCache]) : [$dataCache];
            Cache::set($key,$mergeCache);
        }
    }
}
