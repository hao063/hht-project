<?php

namespace App\Http\Controllers;

use App\Http\Requests\Session\SessionCreateRequest;
use App\Models\Session;

class SessionController extends Controller
{

    //
    public function createSession(SessionCreateRequest $request)
    {
        $dataForm = $request->all();
        // TODO: nếu type bằng 1 normal => cost null
        //       nếu type bằng 2 service => cost không được null

        if ($dataForm['type'] == 1) {
            unset($dataForm['cost']);
        }

        $dataForm['type'] = $dataForm['type'] == 1 ? 'normal' : 'service';

        $data = Session::create($dataForm);
        return $this->responseData($data, 'Session successfully created');
    }

}
