<?php

namespace App\Http\Requests\Session;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Validator;

class SessionCreateRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // TODO: nếu type bằng 1 normal => cost null
        //       nếu type bằng 2 service => cost không được null
        Validator::extend("checkCost", function($field, $value, $params, $validator) {
            if ($this->has('type') && is_null($this->get('type'))) {
                return false;
            }

            if ($this->get('type') == 2) {
                return !is_null($this->get('cost')) && $this->get('cost') != 0;
            }

            return true;
        });


        return [
            'place_id'    => 'required|exists:places,id',
            'type'        => 'required|numeric',
            'title'       => 'required',
            'description' => 'required',
            'vaccinate'   => 'required',
            'start'       => 'required|date_format:Y-m-d H:i:s|before:today|before_or_equal:end',
            'end'         => 'required|date_format:Y-m-d H:i:s|after_or_equal:start',
            'cost'        => 'numeric|checkCost',
        ];
    }

    public function messages()
    {
        return [
            'cost.check_cost' => 'The cost field is required.',
        ];
    }

}
