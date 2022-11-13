<?php

namespace App\Http\Controllers;

use App\Http\Requests\Place\PlaceCreateRequest;
use App\Models\Place;

class PlaceController extends Controller
{

    //
    public function createPlace(PlaceCreateRequest $request): \Illuminate\Http\JsonResponse
    {
        $dataForm = $request->all();
        $data     = Place::create($dataForm);
        return $this->responseData($data, 'Place successfully created');
    }

}
