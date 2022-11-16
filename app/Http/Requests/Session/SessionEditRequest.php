<?php

namespace App\Http\Requests\Session;

use App\Http\Requests\BaseRequest;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class SessionEditRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        Validator::extend("checkCost", function($field, $value, $params, $validator) {
            if (!$this->has('type') && is_null($this->get('type'))) {
                return false;
            }

            if ($this->get('type') == 2) {
                return !is_null($this->get('cost')) && $this->get('cost') != 0;
            }
            return true;
        });

        Validator::extend("checkDataStart", function($field, $value, $params, $validator) {
            if (!$this->has('start') && is_null($this->get('start'))) {
                return false;
            }
            $data = Session::where('id', '<>',$this->session_id)
                ->where('place_id', $this->get('place_id'))
                ->where('start', '<=', $this->get('start'))
                ->where('end', '>=' , $this->get('start'))
                ->get()->toArray();
            return empty($data);
        });


        Validator::extend("checkDataEnd", function($field, $value, $params, $validator) {
            if (!$this->has('end') && is_null($this->get('end'))) {
                return false;
            }
            $data = Session::where('id', '<>',$this->session_id)
                ->where('place_id', $this->get('place_id'))
                ->where('start', '<=', $this->get('end'))
                ->where('end', '>=' , $this->get('end'))
                ->get()->toArray();

            $data2 = Session::where('id', '<>', $this->session_id)
                ->where('place_id', $this->get('place_id'))
                ->where('start', '>=', $this->get('start'))
                ->where('end', '<=' , $this->get('end'))
                ->get()->toArray();
            return empty($data) && empty($data2);
        });

        Validator::extend('checkDateNotFuture', function($field, $value, $params, $validator) {
            if(!$this->has('start') && is_null($this->get('start'))) {
                return false;
            }
            $now = Carbon::today()->timestamp;
            $date = Carbon::parse($this->get('start'))->timestamp;
            return $date > $now;
        });

        return [
            'place_id'    => 'required|exists:places,id',
            'type'        => 'required|numeric',
            'title'       => 'required',
            'description' => 'required',
            'vaccinate'   => 'required',
            'start'       => 'required|date_format:Y-m-d H:i:s|checkDateNotFuture|before_or_equal:end|checkDataStart',
            'end'         => 'required|date_format:Y-m-d H:i:s|after_or_equal:start|checkDataEnd',
            'cost'        => 'checkCost',
        ];
    }
}
