<?php

namespace App\Http\Requests\Tickets;

use App\Http\Requests\BaseRequest;

class TicketCreateRequest extends BaseRequest
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
            'campaign_id'      => 'required|numeric|exists:campaigns,id',
            'name'             => 'required|string|unique:campaign_tickets,name',
            'cost'             => 'required|numeric',
            'type'             => 'required',
            'special_validity' => '',
        ];
    }


}
