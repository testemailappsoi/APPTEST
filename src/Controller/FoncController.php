<?php

namespace App\Controller;

use App\Entity\Fonc;
use App\Form\FoncType;
use App\Form\SearchFoncType;
use App\Repository\FoncRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fonc'), IsGranted("ROLE_UAPP")]
class FoncController extends AbstractController
{
    #[Route('/', name: 'app_fonc_index', methods: ['GET'])]
    public function index(FoncRepository $foncRepository): Response
    {
        return $this->render('fonc/index.html.twig', [
            'foncs' => $foncRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_fonc_new', methods: ['GEt', 'POST'])]
    public function new(Request $request, FoncRepository $foncRepository): Response
    {
        $fonc = new Fonc();
        $form = $this->createForm(FoncType::class, $fonc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $foncRepository->add($fonc, true);
                $this->addFlash('success', 'ajout avec succès');
                return $this->redirectToRoute('app_fonc_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('warning', 'erreur. Vérifier s"il vous plaît !');
            }
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Fausse manipulation');
        }

        return $this->renderForm('fonc/new.html.twig', [
            'fonc' => $fonc,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fonc_show', methods: ['GET'])]
    public function show(Fonc $fonc): Response
    {
        return $this->render('fonc/show.html.twig', [
            'fonc' => $fonc,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fonc_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fonc $fonc, FoncRepository $foncRepository): Response
    {
        $form = $this->createForm(FoncType::class, $fonc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $foncRepository->add($fonc, true);
                $this->addFlash('success', 'modification avec succès');
                return $this->redirectToRoute('app_fonc_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('warning', 'il y a une fausse manipulation');
            }
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('info', 'Fausse manipulation');
        }

        return $this->renderForm('fonc/edit.html.twig', [
            'fonc' => $fonc,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fonc_delete', methods: ['POST'])]
    public function delete(Request $request, Fonc $fonc, FoncRepository $foncRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $fonc->getId(), $request->request->get('_token'))) {
            try {
                $foncRepository->remove($fonc, true);
                $this->addFlash('success', 'suppréssion avec success');
                return $this->redirectToRoute('app_fonc_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('warning', 'Ne peut pas être supprimer');
                return $this->redirectToRoute('app_fonc_index', [], Response::HTTP_SEE_OTHER);
            }
        } elseif (!$this->isCsrfTokenValid('delete' . $fonc->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Fausse manipulation');
            return $this->redirectToRoute('app_fonc_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
