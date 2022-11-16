<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    //

    public function getDataReport($id): \Illuminate\Http\JsonResponse
    {
        $data = Campaign::whereId($id)
            ->where('organizer_id', Auth::id())
            ->first();
        $sessionsForCampaign = $data->getListSessions();
        $dataChartCitizen = [];
        $dataChartCitizen['label'] = 'Citizen';
        $dataChartCitizen['data'] = [];
        $dataChartCitizen['backgroundColor'] = [];
        $dataChartCapacity = [];
        $dataChartCapacity['label'] = 'Capacity';
        $dataChartCapacity['data'] = [];
        $dataChartCapacity['backgroundColor'] = [];
        $dataSessionName = [];
        foreach($sessionsForCampaign as $session){
            $dataChartCitizen['data'][] = $session->Registration->count();
            $dataChartCapacity['data'][] = $session->Place->capacity;
            $dataChartCapacity['backgroundColor'][] = 'blue';
            $dataSessionName[] = $session->title;
            if($session->Place->capacity < $session->Registration->count()){
                $dataChartCitizen['backgroundColor'][] = 'red';
            }
            else{
                $dataChartCitizen['backgroundColor'][] = 'green';
            }
        }
        $data = compact('dataChartCitizen', 'dataChartCapacity', 'dataSessionName');
        return $this->responseData($data);
    }
}
