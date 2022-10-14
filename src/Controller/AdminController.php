<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminEditType;
use App\Repository\UserRepository;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'app_admin_')]

class AdminController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/utilisateur', name: 'utilisateur', methods: ['GET'])]
    public function UserList(UserRepository $userRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/utilisateur/delete/{id}', name: 'udelete', methods: ['GET','POST'])]
    public function delete2(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId() , $request->request->get('token'))) {
            $this->addFlash('error', 'il y a un erreur');
        } else {
            try {
                $userRepository->remove($user, true);
                $this->addFlash('success','suppréssion avec succes'); 
            } catch ( \Exception $e ) {
                $this->addFlash('warning', 'ne peut pas être supprimer');
            }
        }
        return $this->redirectToRoute('app_admin_utilisateur', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/utilisateur/edit/{id}', name: 'utilisateur_modifier', methods: ['GET', 'POST'])]
    public function edit(User $user, Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(AdminEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            try {
                $userRepository->add($user, true);
                $this->addFlash('success', 'Modification avec succès' );
                return $this->redirectToRoute('app_admin_utilisateur', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('warning', 'il y a un erreur');
                return $this->redirectToRoute('utilisateur_modifier', [], Response::HTTP_SEE_OTHER);
            }         
        }
        elseif ($form->isSubmitted() && ! $form->isValid()) {
            $this->addFlash('error', 'Fausse manipulation');
            return $this->redirectToRoute('utilisateur_modifier', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->renderForm('admin/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
