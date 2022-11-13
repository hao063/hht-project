<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tickets\TicketCreateRequest;
use App\Models\CampaignTicket;

class TicketController extends Controller
{

    //
    public function createTicket(TicketCreateRequest $request): \Illuminate\Http\JsonResponse
    {
        $dataForm = $request->all();

        // TODO:  luu ý 0 là kiểu amount
        //              1 là kiểu date
        //              2 là không dùng amount, date (Ticket Vip)

        if ($dataForm['type'] != 2) {
            $type                         = $dataForm['type'] == 0 ? 'amount' : 'date';
            $dataSpecialValidity          = [
                'type' => $type,
                $type  => $dataForm['special_validity'],
            ];
            $dataForm['special_validity'] = json_encode($dataSpecialValidity);
        } else {
            unset($dataForm['special_validity']);
        }
        unset($dataForm['type']);
        $data = CampaignTicket::create($dataForm);
        return $this->responseData($data, 'Ticket successfully created');
    }

}
