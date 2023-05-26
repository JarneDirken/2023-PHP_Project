<?php

namespace App\Http\Livewire;
use App\Mail\InschrijvingEvent;
use App\Mail\PaymentMail;
use App\Mail\QuestionMail;
use App\Mail\WelcomeMaill;
use App\Models\Event;
use App\Models\MailTemplate;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class Mailing
{
    //de verschillende soorten mails versturen met de nodige variables
    public function sendMail($id) {
        $templateName = 'Inschrijving';
        $columnToRetrieve = 'body';

        $templateC = MailTemplate::where('name', $templateName)->select($columnToRetrieve)->first()->body;
        $eventName = Event::where('id', $id)->value('name');
        //variabelen overzetten naar de mail
        $template = new InschrijvingEvent([
            'name' => Auth::user()->email,
            'event' => $eventName,
            'content' => $templateC,
        ]);
        $to = new Address(Auth::user()->email);
        //mail versturen
        Mail::to($to)
            ->send($template);

    }
    //welkom mail versturen
    public static function sendWelcome($mail,$name) {
        $templateName = 'Inschrijving';
        $columnToRetrieve = 'body';

        $templateC = MailTemplate::where('name', $templateName)->select($columnToRetrieve)->first()->body;
        //variabelen overzetten
        $template = new welcomeMaill([
            'content' => $templateC,
            'name' => $name,
        ]);
        $to = new Address($mail);
        Mail::to($to)
            ->send($template);

    }
    //mail versturen wanneer er een bericht wordt gestuurd
    public static function sendQuestion($question,$frommail) {

        $template = new QuestionMail([
            'frommail' => $frommail,
            'question' =>$question
        ]);
        $to = new Address('Info.tveacontact@gmail.com');
        Mail::to($to)
            ->send($template);

    }
    //betaalmail versturen
    public static function sendPayment($mail,$name) {
        $templateName = 'Betaling';
        $columnToRetrieve = 'body';

        $templateC = MailTemplate::where('name', $templateName)->select($columnToRetrieve)->first()->body;

        $template = new PaymentMail([
            'content' => $templateC,
            'name' => $name,
        ]);
        $to = new Address($mail);
        Mail::to($to)
            ->send($template);

    }
    //mail versturen wanneer er iets wordt gekocht
    public static function sendShopMail($mail,$name) {
        $templateName = 'Bestelling';
        $columnToRetrieve = 'body';

        $templateC = MailTemplate::where('name', $templateName)->select($columnToRetrieve)->first()->body;

        $template = new PaymentMail([
            'content' => $templateC,
            'name' => $name,
        ]);
        $to = new Address($mail);
        Mail::to($to)
            ->send($template);

    }
}
