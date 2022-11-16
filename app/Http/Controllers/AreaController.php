<?php

namespace App\Http\Controllers;



use App\Http\Requests\Area\AreaCreateRequest;
use App\Models\Area;
use App\Models\Campaign;

class AreaController extends Controller
{
    //
    public function getAreas($id)
    {
        $data = Campaign::whereId($id)->with('Areas')->first();
        return $this->responseData($data->areas->toArray());
    }

    public function createArea(AreaCreateRequest $request): \Illuminate\Http\JsonResponse
    {
        $dataForm = $request->all();
        $data = Area::create($dataForm);
        return $this->responseData($data, 'Area successfully created');
    }
}
