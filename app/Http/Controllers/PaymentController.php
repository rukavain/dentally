<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function pay(){


        dd("asd");

    }

    public function success(){

        $sessionId = Session::get('session_id');

        $response = Curl::to('https://api.paymongo.com/v1/checkout_sessions/'.$sessionId)
            ->withHeader('Content-Type: application/json')
            ->withHeader('Authorization: Basic c2tfdGVzdF9NMU5EOUpzRWtCNVQyNUxCZ0hySEwyZmk6')
            ->asJson()
            ->get();

        dd($response);
    }

}
