<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class LetterMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public $mailCompany, public $namepdf)
    {
        $this->subject = "Solicitação de Dados Cadastrais";
       // $this->attachData($this->letterpdf,"Anexo.pdf");

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->attach( Storage::disk( 'public' )->path( '\\letters\\'.$this->namepdf ), [
        'as' => 'Anexo.pdf',
        'mime' => 'application/pdf',
    ] );

        return $this->view('mail.sendmail');
    }
}
