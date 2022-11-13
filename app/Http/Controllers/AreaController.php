<?php

namespace App\Http\Controllers;



use App\Http\Requests\Area\AreaCreateRequest;
use App\Models\Area;

class AreaController extends Controller
{
    //
    public function createArea(AreaCreateRequest $request): \Illuminate\Http\JsonResponse
    {
        $dataForm = $request->all();
        $data = Area::create($dataForm);
        return $this->responseData($data, 'Area successfully created');
    }
}
