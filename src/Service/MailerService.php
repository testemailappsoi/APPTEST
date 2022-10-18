<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailerService
{
    public function __construct(private MailerInterface $mailer , private Environment $twig) {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }
    public function sendEmail(
        string $from,
        string $to,
        string $subject,
        string $template,
        array $parameters
    ): void
    {
        $email = (new Email())
            ->from($from)
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            // ->replyTo($this->replyTo)
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
//            ->text('Sending emails is fun again!')
            ->html(
                $this->twig->render($template, $parameters),
                charset: 'text/html'
                );
             $this->mailer->send($email);
        // ...
    }

}