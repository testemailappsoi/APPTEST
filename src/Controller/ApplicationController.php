<?php

namespace App\Controller;

use App\Entity\Application;
use App\Form\ApplicationType;
use App\Repository\ApplicationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/application'), IsGranted("ROLE_UAPP")]
class ApplicationController extends AbstractController
{
    #[Route('/', name: 'app_application_index', methods: ['GET', 'POST'])]
    public function index(ApplicationRepository $applicationRepository): Response
    {

        return $this->render('application/index.html.twig', [
            'applications' => $applicationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_application_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ApplicationRepository $applicationRepository): Response
    {
        $application = new Application();
        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $applicationRepository->add($application, true);
                $this->addFlash('success', 'ajout avec succès');
                return $this->redirectToRoute('app_application_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('warning', 'erreur. Vérifier s"il vous plaît !');
            }
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Fausse manipulation');
        }

        return $this->renderForm('application/new.html.twig', [
            'application' => $application,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_application_show', methods: ['GET'])]
    public function show(Application $application): Response
    {
        return $this->render('application/show.html.twig', [
            'application' => $application,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_application_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Application $application, ApplicationRepository $applicationRepository): Response
    {
        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $applicationRepository->add($application, true);
                $this->addFlash('success', 'modification avec succès');
                return $this->redirectToRoute('app_application_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('warning', 'il y a une fausse manipulation');
            }
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('info', 'Fausse manipulation');
        }
        return $this->renderForm('application/edit.html.twig', [
            'application' => $application,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_application_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Application $application, ApplicationRepository $applicationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $application->getId(), $request->request->get('_token'))) {
            try {
                $applicationRepository->remove($application, true);
                $this->addFlash('success', 'suppréssion avec success');
                return $this->redirectToRoute('app_application_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('warning', 'Suppression impossible');
                return $this->redirectToRoute('app_application_index', [], Response::HTTP_SEE_OTHER);
            }
        } elseif (!$this->isCsrfTokenValid('delete' . $application->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Fausse manipulation');
            return $this->redirectToRoute('app_application_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
