<?php

namespace App\Http\Requests\Area;

use App\Http\Requests\BaseRequest;

class AreaCreateRequest extends BaseRequest
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
            'campaign_id' => 'required|exists:campaigns,id',
            'name'        => 'required|unique:areas,name',
        ];
    }

}
