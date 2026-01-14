<?php
// app/Mail/RigSignatureRequest.php

namespace App\Mail;

use App\Models\TimeRegister;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RigSignatureRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $timeRegister;

    public function __construct(TimeRegister $timeRegister)
    {
        $this->timeRegister = $timeRegister;
    }

    public function build()
    {
        // dd($this->timeRegister->getSignatureUrl());
        return $this->subject('Rig Representative Signature Required - Time Register')
                    ->view('emails.rig-signature-request')
                    ->with([
                        'timeRegister' => $this->timeRegister,
                        'signatureUrl' => $this->timeRegister->getSignatureUrl(),
                    ]);
    }
}