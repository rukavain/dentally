<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function build()
    {
        return $this->from(config('mail.from.address')) // Use the MailSlurp email address
                    ->subject($this->details['title'])  // Subject from details
                    ->view('emails.test')               // Email view (blade file)
                    ->with('details', $this->details);  // Pass data to the view
    }
}

