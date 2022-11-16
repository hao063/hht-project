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

    public function getCampaign($id): \Illuminate\Http\JsonResponse
    {
        try {
            $campaigns = Campaign::whereId($id)->first();
            return $this->responseData($campaigns->toArray());
        }catch (\Throwable $e) {
            return $this->responseData([], $e->getMessage(), 500);
        }
    }

    public function editCampaign(CampaignEditRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        $dataForm = $request->all();
        $data     = Campaign::whereId($id)->update($dataForm);
        return $this->responseData($data, "Event successfully updated");
    }

    public function detailCampaign($id)
    {
        try {
            $campaign                     = Campaign::with([
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
            $dataResponse                 = [];
            $data                         = $campaign->toArray();
            $dataResponse['name']         = $data['name'];
            $dataResponse['organizer_id'] = $data['organizer_id'];
            $dataResponse['slug']         = $data['slug'];
            $dataResponse['id']           = $data['id'];
            $dataResponse['date']         = Carbon::parse($data['date'])->format('d-m-Y');


            foreach ($data['areas'] as $key => $area) {
                $dataResponse['areas'][$key]['id']   = $area['id'];
                $dataResponse['areas'][$key]['name'] = $area['name'];
                $dataResponse['areas'][$key]['place_total'] = count($area['places']);
                $totalSession = 0;
                foreach ($area['places'] as $itemPlace) {
                    $dataResponse['places'][$itemPlace['id']]['id']       = $itemPlace['id'];
                    $dataResponse['places'][$itemPlace['id']]['area_id']  = $itemPlace['area_id'];
                    $dataResponse['places'][$itemPlace['id']]['capacity'] = $itemPlace['capacity'];
                    $dataResponse['places'][$itemPlace['id']]['name']     = $itemPlace['name'];
                    $totalSession += count($itemPlace['sessions']);
                    foreach ($itemPlace['sessions'] as $session) {
                        $dataResponse['sessions'][$session['id']]['id']          = $session['id'];
                        $dataResponse['sessions'][$session['id']]['cost']        = $session['cost'];
                        $dataResponse['sessions'][$session['id']]['description'] = $session['description'];
                        $dataResponse['sessions'][$session['id']]['end']         = Carbon::parse($session['end'])
                            ->format('H:i');
                        $dataResponse['sessions'][$session['id']]['place']       = $session['place'];
                        $dataResponse['sessions'][$session['id']]['place_id']    = $session['place_id'];
                        $dataResponse['sessions'][$session['id']]['start']       = Carbon::parse($session['start'])
                            ->format('H:i');
                        $dataResponse['sessions'][$session['id']]['title']       = $session['title'];
                        $dataResponse['sessions'][$session['id']]['type']        = $session['type'];
                        $dataResponse['sessions'][$session['id']]['vaccinate']   = $session['vaccinate'];
                        $dataResponse['sessions'][$session['id']]['place_name']  = $session['place']['name'];
                        $dataResponse['sessions'][$session['id']]['vaccinate']   = $session['place']['area']['name'];
                    }
                }
                $dataResponse['areas'][$key]['session_total'] = $totalSession;
            }

            $dataResponse['tickets'] = $campaign->Tickets->map(function($ticket) {
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
                    $dataTicket['special_validity'] = Carbon::parse($dataSpecialValidity['date'])->format('d-m-Y');
                } elseif ($dataSpecialValidity['type'] == 'amount') {
                    $dataTicket['type']             = $dataSpecialValidity['type'];
                    $dataTicket['special_validity'] = $dataSpecialValidity['amount'];
                }
                return $dataTicket;
            })->toArray();

            return $this->responseData($dataResponse);
        } catch (\Throwable $e) {
            return $this->responseData($e->getMessage(), "Error", 500);
        }
    }

}
