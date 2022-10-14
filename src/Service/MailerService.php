<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private $replyTo;
    public function __construct(private MailerInterface $mailer) {
    }
    public function sendEmail(
        $to = 'asstabano@gmail.com',
        $content = '<p>See Twig integration for better HTML integration!</p>',
        $subject = 'Time for Symfony Mailer!'
    ): void
    {
        $email = (new Email())
            ->from('testemailappsoi2@gmail.com')
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            // ->replyTo($this->replyTo)
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
//            ->text('Sending emails is fun again!')
            ->html($content);
             $this->mailer->send($email);
        // ...
    }

}