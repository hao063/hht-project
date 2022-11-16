<?php

namespace App\Http\Controllers;

use App\Http\Requests\Session\SessionCreateRequest;
use App\Http\Requests\Session\SessionEditRequest;
use App\Models\Area;
use App\Models\Session;

class SessionController extends Controller
{

    //
    public function createSession(SessionCreateRequest $request)
    {
        $dataForm = $request->all();
        // TODO: nếu type bằng 1 normal => cost null
        //       nếu type bằng 2 service => cost không được null

        if ($dataForm['type'] == 1) {
            unset($dataForm['cost']);
        }

        $dataForm['type'] = $dataForm['type'] == 1 ? 'normal' : 'service';

        $data = Session::create($dataForm);
        return $this->responseData($data, 'Session successfully created');
    }

    public function getEditSession($session_id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = Session::whereId($session_id)->first();
            return $this->responseData($data);

        }catch (\Throwable $e) {
            return $this->responseData($e->getMessage(), 'error', 500);

        }
    }

    public function editSession(SessionEditRequest $request, $id)
    {
        $dataForm = $request->all();
        if ($dataForm['type'] == 1) {
            unset($dataForm['cost']);
        }

        $dataForm['type'] = $dataForm['type'] == 1 ? 'normal' : 'service';

        $data = Session::whereId($id)->update($dataForm);
        return $this->responseData($data, 'Session successfully updated');
    }

    public function getPlaceAndArea($id): \Illuminate\Http\JsonResponse
    {
        $data = Area::with('Places')->where('campaign_id', $id)->get();
        $dataAreaPlace = [];
        foreach ($data as  $area) {
            foreach ($area->places as  $valuePlace) {
                $dataAreaPlace[$valuePlace->id]['id'] = $valuePlace->id;
                $dataAreaPlace[$valuePlace->id]['area_name'] = $area->name;
                $dataAreaPlace[$valuePlace->id]['place_name'] = $valuePlace->name;
            }
        }
        return $this->responseData($dataAreaPlace);
    }

}
