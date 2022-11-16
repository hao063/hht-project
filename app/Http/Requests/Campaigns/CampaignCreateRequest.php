<?php

namespace App\Http\Requests\Campaigns;


use App\Http\Requests\BaseRequest;

class CampaignCreateRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:campaigns,slug',
            'slug' => 'required|unique:campaigns,slug|regex:/^[a-z0-9]+$/',
            'date' => 'required|date_format:Y-m-d',
        ];
    }

    public function messages()
    {
        return [
            'slug.regex' => "Slug must not be empty and only contain a-z, 0-9 and '-'",
        ];
    }

}
