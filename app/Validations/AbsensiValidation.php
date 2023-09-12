<?php

namespace App\Validations;

use App\Traits\RequestAbleTrait;
use App\Utils\DayUtil;
use Illuminate\Support\Facades\Validator;

class AbsensiValidation
{
    use RequestAbleTrait;

    public function __construct(
        protected array $request
    ){}

    public function validation(){
        $validation = Validator::make($this->request,[
            'type' => [
                'required',
                function($attribute, $value, $fail){
                    if(!in_array($value,['in','out'])){
                        $fail("type absen hanya [ in atau out ]");
                    }
                    if(!DayUtil::IsRequestAble(date('D'))->checkingDay()){
                        $fail("Error, Check in absen hanya di hari senin-jumat");
                    }
                }
            ]
        ]);

        if($validation->fails()){
            return [
                'code' => 422,
                'message' => $validation->errors()->first(),
            ];
        }
        if($err = $this->flowBusiness()){
            return ['code' => 400 , 'message' => $err];
        }

        return ['code' => 200];
    }

    protected function flowBusiness(){
        $data = $this->request;
        if(is_null($data['absen']) && $data['type'] == 'out'){
            return 'Anda belum absen masuk.';
        }
        if(!is_null($data['absen']) && !is_null($data['absen']->check_in) && $data['type'] == 'in'){
            return 'Anda sudah absen masuk.';
        }
        if(!is_null($data['absen']) && date('H:i') < '16:00'){
            return 'Belum bisa absen keluar, absen keluar 16.00';
        }
        if(!is_null($data['absen']) && !is_null($data['absen']->check_in) && !is_null($data['absen']->check_out)){
            return 'Anda sudah absen masuk / keluar';
        }

        return false;
    }

}
