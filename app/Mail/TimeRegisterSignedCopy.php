<?php
// app/Mail/TimeRegisterSignedCopy.php

namespace App\Mail;

use App\Models\TimeRegister;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class TimeRegisterSignedCopy extends Mailable
{
    use Queueable, SerializesModels;

    public $timeRegister;

    public function __construct(TimeRegister $timeRegister)
    {
        $this->timeRegister = $timeRegister;
    }

    public function build()
    {
        // Generate PDF from view
        $pdf = Pdf::loadView('time-registers.pdf', [
            'timeRegister' => $this->timeRegister
        ]);

        $filename = 'Job_Carried_Out_Report_' . $this->timeRegister->well_no . '_' . now()->format('Y-m-d-His') . '.pdf';

        return $this->subject('Job Carried Out Report Signed Copy - ' . $this->timeRegister->well_no)
                    ->view('emails.time-register-signed-copy')
                    ->with([
                        'timeRegister' => $this->timeRegister,
                    ])
                    ->attachData($pdf->output(), $filename, [
                        'mime' => 'application/pdf',
                    ]);
    }
}
