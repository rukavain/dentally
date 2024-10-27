<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct() {}

    public function build()
    {
        return $this->from('you@yourdomain.com')
                    ->to('recipient@example.com')
                    ->subject('Test Email from Deployed Laravel App')
                    ->view('emails.test');
    }
}
