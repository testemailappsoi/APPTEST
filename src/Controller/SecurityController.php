<?php

namespace App\Controller;

use App\Form\LiensType;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $this->addFlash('success', 'Connecté');
            return $this->redirectToRoute('app_reponse_index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        $this->addFlash('info', 'Déconnecté');
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    //envoi de liens de connexion
    #[Route('/liens', name: 'app_liens', methods: ['GET', 'POST'])]
    public function LiensConnexion(UserRepository $userTemp ,Request $request, MailerService $mailerService, LoginLinkHandlerInterface $loginLinkHandlerInterface): Response
    {
        $form = $this->createForm(LiensType::class);
        $form->handleRequest($request);
        $value = 42;
        $userm = $userTemp->findOneBy(['id' => $value]);
        $loginLinkDetails = $loginLinkHandlerInterface->createLoginLink($userm);

        if ($form->isSubmitted() && $form->isValid()) {
              $data = $form->getData();
            //   dd($loginLinkDetails);
              $url = $loginLinkDetails->getUrl();
              $mailerService->sendEmail(
                from: ('testemailappsoi2@gmail.com'),
                to: $data['Email'] ,
                subject: ('AppSoi Gestion Incident Liens de connexion temporaire:'),
                template:"emails/liens.html.twig",parameters:[
                    "Email" => $data['Email'],
                    "Liens" => $url ]
              );
        $this->addFlash('success', 'Lien Envoyer');
        return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Fausse manipulation');
        }

        return $this->renderForm('/security/liens.html.twig', [
            'form' => $form,
        ]);
    }
}
