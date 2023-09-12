<?php

namespace App\Services\Absensi;

interface AbsensiServiceInterface
{
    public function create(array $data) : array;
    public function find(int $id_karyawan,string $checkin);
    public function get(int $id_karyawan,string $date);
}
