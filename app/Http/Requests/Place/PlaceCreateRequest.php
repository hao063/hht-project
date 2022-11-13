<?php

namespace App\Http\Requests\Place;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class PlaceCreateRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
            'area_id'  => 'required|exists:areas,id',
            'name'     => 'required|unique:places,name',
            'capacity' => 'required|numeric',
        ];
    }

}
