<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\HelloMail;

class EmailController extends Controller
{
    // public function sendHelloEmail(){
    //     $toEmail = 'johnmanaloto73@gmail.com';
    //     $mailmessage = 'GUMANA KA PLESSSS';
    //     $subject = 'Welcome brokoloyd';

    //     Mail::to($toEmail)->send(new HelloMail($mailmessage, $subject));
    // }

}
