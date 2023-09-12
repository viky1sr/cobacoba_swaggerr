<?php

namespace App\Services\Absensi;

use App\Jobs\AbsenAkumulasiJob;
use App\Repositories\AbsensiRepository;
use App\Validations\AbsensiValidation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AbsensiService implements AbsensiServiceInterface
{

    public function __construct(
        protected AbsensiRepository $absensiRepository,
    ){}

    public function create(array $data): array
    {
        $getAbsen = $this->find(1,date('Y-m-d'));
        $err = AbsensiValidation::IsRequestAble(array_merge($data,['absen' => $getAbsen]))->validation();
        if($err['code'] != 200){
            return ['status' => false ,'message' => $err['message'], 'code' => $err['code']];
        }
        $flied = ($data['type'] == 'in') ? 'check_in' : 'check_out';
        $insert = $this->absensiRepository->create([
            'id_karyawan' => 1,
            $flied => $data['date_time'],
        ]);
        AbsenAkumulasiJob::dispatch($insert);
        return ['status' => true ,'message' => 'Success', 'code' => 201];
    }

    public function find(int $id_karyawan, string $checkin)
    {
        return $this->absensiRepository->find($id_karyawan ?: 1,$checkin);
    }

    public function get(int $id_karyawan, string $date)
    {
        $gp = 2000000;
        $deduction = 0;
        $tunjangan  = 0;
        $id = $id_karyawan ?: 1;
        $year = (int)substr($date,0,4);
        $month = (int)substr($date,5,2);
        $key = 'id_karyawan:'.$id.'_'.$date;
        $getCache = Cache::get($key);
        $dataAbsen = $this->absensiRepository->get($id,$year,$month);
        if(count($dataAbsen) == 0){
            return [];
        }
        if(count($getCache) == count($dataAbsen)){
            foreach($getCache as $item){
                $tunjangan += ($item['deduction'] == 0) ? 15000 : 0;
                $deduction += (int)$item['deduction'];
            }
        }else {
            foreach ($dataAbsen as $item){
                $masuk = strtotime('07:00:00');
                $time = strtotime(Carbon::parse($item->check_in)->format("H:i:s"));
                $lateness = ceil(($time - $masuk) / 60);
                $deduction = 0;
                if($lateness >= 15){
                    $deduction += 5000;
                } else if($lateness >= 30){
                    $deduction += 10000;
                } else {
                    $tunjangan += 15000;
                }
                AbsenAkumulasiJob::dispatch($item);
            }
        }

        return [
           'components' =>  [
               ['name' => 'Gaji Pokok' , 'amount' => $gp],
               ['name' => 'Tunjangan Kinerja','amount' => $tunjangan],
               ['name' => 'Potongan Keterlambatan' , 'amount' => -$deduction],
           ],
            'take_home_pay' => ($gp + $tunjangan) - $deduction
        ];
    }
}
