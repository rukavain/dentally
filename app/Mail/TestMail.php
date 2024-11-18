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
        // return $this->from('no-reply@toothimpressionsdentalclinic.xyz')
        //             ->to('magtoto599@gmail.com')
        //             ->subject('Test Email from Deployed Laravel App')
        //             ->view('emails.test');

        return $this->view('emails.mail')
                    ->subject('Mailjet Test Email');
    }
}
