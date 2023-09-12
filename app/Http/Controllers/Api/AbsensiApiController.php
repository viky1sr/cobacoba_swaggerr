<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Absensi\AbsensiService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiApiController extends Controller
{
    use ResponseTrait;
    public function __construct(
        protected AbsensiService $absensiService
    ){}

    public function presences(Request $request) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $insertAbsen = $this->absensiService->create(array_merge($request->all(),['date_time' => date('Y-m-d H:i:s')]));
            if($insertAbsen['status']){
                DB::commit();
                return $this->success($insertAbsen['message'],$insertAbsen['code']);
            }
            return $this->failure($insertAbsen['message'],$insertAbsen['code']);
        }catch (\Exception $e){
            DB::rollBack();
            return $this->failure($e->getMessage(),400);
        }
    }

    public function payslips(Request $request) : JsonResponse
    {
        try {
            $karyawanAbsen = $this->absensiService->get(1,$request->month);
            return  $this->success('Success',200,$karyawanAbsen);
        }catch (\Exception $e){
            return  $this->failure($e->getMessage(),400);
        }
    }

}
