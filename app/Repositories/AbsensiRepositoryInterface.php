<?php

namespace App\Repositories;

use App\Models\Absensi;

interface AbsensiRepositoryInterface
{
    public function create(array $data) : Absensi;
    public function find(int $id_karyawan,string $checkin);
    public function get(int $id_karyawan, int $year,int $month);
}
