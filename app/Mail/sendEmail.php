<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendEmail extends Mailable
{
    use Queueable, SerializesModels;
    private $validationResult, $headerValidation;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($validationResult, $headerValidation)
    {
        $this->validationResult = $validationResult;
        $this->headerValidation = $headerValidation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.uploads.result')
        ->with([
            'validationResult' => $this->validationResult, 
            'headerValidation' => $this->headerValidation
        ]);
    }
}
