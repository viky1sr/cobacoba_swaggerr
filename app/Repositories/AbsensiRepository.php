<?php

namespace App\Repositories;

use App\Models\Absensi;

class AbsensiRepository implements AbsensiRepositoryInterface
{
    public function __construct(
        protected Absensi $model
    ){}

    public function create(array $data): Absensi
    {
        return $this->model->create($data);
    }

    public function find(int $id_karyawan, string $checkin)
    {
        return $this->model
            ->select("check_in","check_out")
            ->where("id_karyawan",'=', $id_karyawan)
            ->whereDate("check_in" , $checkin)
            ->first();
    }

    public function get(int $id_karyawan, int $year,int $month)
    {
        return $this->model
            ->select("check_in","check_out")
            ->where("id_karyawan",'=', $id_karyawan)
            ->whereYear("check_in" , $year)
            ->whereMonth("check_in" , $month)
            ->get();
    }
}
