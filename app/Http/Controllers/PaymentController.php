<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function pay(){

        $data = [
            'data' => [
                'attributes' => [
                        'line_items' => [
                                [
                                    'currency' => 'PHP',
                                    'amount' => 1000, //10000 = 100PESOS
                                    'description' => 'text',
                                    'name' => 'Test Product',
                                    'quantity' => 1,
                                ]
                            ],
                        'payment_method_types' => [
                            'gcash',
                        ],
                        'success_url' => 'https://toothimpressionsdentalclinic.xyz/success',
                        'cancel_url' => 'https://toothimpressionsdentalclinic.xyz',
                        'description' => 'text',
                    ]
                ]

            ];


        $response = Curl::to("https://api.paymongo.com/v1/checkout_sessions")
            ->withHeader('Content-Type: application/json')
            ->withHeader('accept: application/json')
            ->withHeader('Authorization: Basic c2tfdGVzdF9NMU5EOUpzRWtCNVQyNUxCZ0hySEwyZmk6')
            ->withData($data)
            ->asJson()
            ->withOption('SSL_VERIFYHOST', false)
            ->withOption('SSL_VERIFYPEER', false)
            ->post();


        Session::put('session_id', $response->data->id);

        return redirect()->to($response->data->attributes->checkout_url);
    }

    public function success(){
        $user_id = Auth::user();

        $sessionId = Session::get('session_id');

        $response = Curl::to('https://api.paymongo.com/v1/checkout_sessions/'.$sessionId)
            ->withHeader('Content-Type: application/json')
            ->withHeader('Authorization: Basic c2tfdGVzdF9NMU5EOUpzRWtCNVQyNUxCZ0hySEwyZmk6')
            ->asJson()
            ->get();

            return redirect()->intended(RouteServiceProvider::HOME);
    }

}
