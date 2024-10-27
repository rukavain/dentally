<?php
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;

Route::get('/send-test-mail', function () {
    Mail::send(new TestMail());
    return 'Test email sent!';
});
