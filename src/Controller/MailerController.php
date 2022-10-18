<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('testemailappsoi2@gmail.com')
            ->to('asstabano@gmail.com')
            ->subject('Hello mail!')
            ->text('Sending emails mandeh!')
            ->html('<p>Mandeh ty raha ty !</p>');

        $mailer->send($email);

        // ...
        return $this->render('mailer/index.html.twig');
    }
}
