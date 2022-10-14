<?php

namespace App\Controller;

use App\Entity\Rout;
use App\Form\RoutType;
use App\Repository\RoutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rout')]
class RoutController extends AbstractController
{
    #[Route('/', name: 'app_rout_index', methods: ['GET'])]
    public function index(RoutRepository $routRepository): Response
    {
        return $this->render('rout/index.html.twig', [
            'routs' => $routRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_rout_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RoutRepository $routRepository): Response
    {
        $rout = new Rout();
        $form = $this->createForm(RoutType::class, $rout);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $routRepository->add($rout, true);
                $this->addFlash('success', 'ajout avec succès');
                return $this->redirectToRoute('app_rout_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('warning', 'erreur. Vérifier svp !');
            }
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Fausse manipulation');
        }

        return $this->renderForm('rout/new.html.twig', [
            'rout' => $rout,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rout_show', methods: ['GET'])]
    public function show(Rout $rout): Response
    {
        return $this->render('rout/show.html.twig', [
            'rout' => $rout,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rout_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rout $rout, RoutRepository $routRepository): Response
    {
        $form = $this->createForm(RoutType::class, $rout);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $routRepository->add($rout, true);
                $this->addFlash('success', 'modification avec succès');
                return $this->redirectToRoute('app_rout_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('warning', 'il y a une fausse manipulation');
            }
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('info', 'Fausse manipulation');
        }

        return $this->renderForm('rout/edit.html.twig', [
            'rout' => $rout,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rout_delete', methods: ['POST'])]
    public function delete(Request $request, Rout $rout, RoutRepository $routRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rout->getId(), $request->request->get('_token'))) {
            try {
                $routRepository->remove($rout, true);
                $this->addFlash('success', 'suppréssion avec success');
                return $this->redirectToRoute('app_rout_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('warning', 'Suppression impossible');
                return $this->redirectToRoute('app_rout_index', [], Response::HTTP_SEE_OTHER);
            }
        } elseif (!$this->isCsrfTokenValid('delete' . $rout->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Fausse manipulation');
            return $this->redirectToRoute('app_rout_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
