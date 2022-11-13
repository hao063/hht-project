<?php

namespace App\Http\Controllers;

use App\Http\Requests\Campaigns\CampaignCreateRequest;
use App\Http\Requests\Campaigns\CampaignEditRequest;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{

    //
    public function getListCampaigns(): \Illuminate\Http\JsonResponse
    {
        $campaigns = Campaign::where('organizer_id', Auth::id())->get();
        $data      = $campaigns->map(function($campaign) {
            $campaign['date'] = Carbon::parse($campaign['date'])->format('F d Y');
            return $campaign;
        });
        return $this->responseData($data);
    }

    public function createCampaign(CampaignCreateRequest $request): \Illuminate\Http\JsonResponse
    {
        $dataForm                 = $request->all();
        $dataForm['organizer_id'] = Auth::id();
        $data                     = Campaign::create($dataForm);
        return $this->responseData($data, "Event successfully created");
    }

    public function editCampaign(CampaignEditRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        $dataForm = $request->all();
        $data     = Campaign::whereId($id)->update($dataForm);
        return $this->responseData($data, "Event successfully updated");
    }

    public function detailCampaign($id): \Illuminate\Http\JsonResponse
    {
        try {
            $campaign = Campaign::with([
                'Areas' => function($query) {
                    $query->with([
                        'Places' => function($query2) {
                            $query2->with([
                                'Sessions' => function($query3) {
                                    $query3->with([
                                        'Place' => function($query4) {
                                            $query4->with('Area');
                                        },
                                    ]);
                                },
                            ]);
                        },
                    ]);
                },
                'Tickets',
            ])->whereId($id)->first();

            $data = $campaign->toArray();

            $data['tickets'] = $campaign->Tickets->map(function($ticket) {
                $dataTicket                = [];
                $dataTicket['id']          = $ticket['id'];
                $dataTicket['campaign_id'] = $ticket['campaign_id'];
                $dataTicket['name']        = $ticket['name'];
                $dataTicket['cost']        = $ticket['cost'];
                $dataSpecialValidity       = json_decode($ticket['special_validity'], true);
                if (is_null($dataSpecialValidity)) {
                    $dataTicket['type']             = null;
                    $dataTicket['special_validity'] = null;
                } elseif ($dataSpecialValidity['type'] == 'date') {
                    $dataTicket['type']             = $dataSpecialValidity['type'];
                    $dataTicket['special_validity'] = $dataSpecialValidity['date'];
                } elseif ($dataSpecialValidity['type'] == 'amount') {
                    $dataTicket['type']             = $dataSpecialValidity['type'];
                    $dataTicket['special_validity'] = $dataSpecialValidity['amount'];
                }
                return $dataTicket;
            })->toArray();
            return $this->responseData($data);
        } catch (\Throwable $e) {
           return $this->responseData($e->getMessage(), "Error", 500);
        }
    }

}
