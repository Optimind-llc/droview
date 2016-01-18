<?php

namespace App\Http\Controllers\Backend\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Access\User;
use App\Models\Ticket;
use App\Models\Pin;

class PinController extends Controller
{

    public function index()
    {
        //return view('backend.pin.index', compact('tickets', 'sum'));
        return view('backend.pin.index', compact('tickets', 'sum'));
    }

    public function outPutHTML(Request $inputs)
    {
        $pins = $this->generatePin($inputs['num'], $inputs['circulation']);

        return view('backend.pin.outPutPin', compact('pins'));
    }

    public function outPutMail(Request $inputs)
    {
        $pins = $this->generatePin($inputs['num'], $inputs['circulation']);
        $mail = $inputs['mail'];
        //メールの送信
        \Mail::send('emails.sendPinCode', ['pins' => $pins], function($message)
        {
            $message->to('s.shiichi311041@gmail.com', 'John Smith')->subject('PINコードを発行しました');
        });

        return redirect(url('/admin/pin'))->with('message', 'メール送信しました');
    }

    public function generatePin($num, $circulation)
    {
        $pins = array();

        $n = 1;
        while ($n <= $circulation)
        {
            $pin = new Pin;
            $pin->pin = str_random(16);
            $pin->numberOfTickets = $num;
            $pin->status = '0';
            $pin->save();

            $pins["$n"]["pinCode"] = $pin['pin'];
            $pins["$n"]["numberOfTickets"] = $pin['numberOfTickets'];
            $n += 1;
        }

        return $pins;
    }
}
