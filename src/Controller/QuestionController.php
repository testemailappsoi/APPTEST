<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\ChoixResponsableType;
use App\Form\FaqType;
use App\Form\MailType;
use App\Form\QuestionType;
use App\Form\QuestType;
use App\Form\ReponseType;
use App\Form\SearchQuestionType;
use App\Repository\QuestionRepository;
use App\Service\MailerService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class QuestionController extends AbstractController
{
    #[Route('/SansRéponse', name: 'app_question_index', methods: ['GET'])]
    public function index(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/User/indexSansReponse.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }

    #[Route('/', name: 'app_reponse_index', methods: ['GET', 'POST'])]
    public function Reponse(QuestionRepository $questionRepository, Request $request): Response
    {
        $rechquestions = $questionRepository->findBy(['FAQ' => true], ['DateQuest' => 'ASC']);
        $form = $this->createForm(SearchQuestionType::class);
        $search = $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // On recherche les annonces correspondant aux mots clés
            $rechquestions = $questionRepository->search($search->get('mots')->getData());
            if (!$rechquestions) {
                $this->addFlash('info', 'Le mots saisi est introuvable');
            }
        }

        return $this->render('question/indexFAQ.html.twig', [
            'rechquestions' => $rechquestions,
            'questions' => $questionRepository->findAll(),
            'form' => $form->createView()
        ]);
    }

    #[Route('/question/Responsable/SansRep', name: 'app_responsable_index', methods: ['GET'])]
    public function ResponsableSansReponse(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/responsable/indexSansReponse.html.twig', [
            'questions' => $questionRepository->findBy(['Finished' => false], ['updateAt' => 'DESC'])
        ]);
    }

    #[Route('/question/Responsable/Question/SansRep', name: 'app_responsable_QSR_index', methods: ['GET'])]
    public function FiltreSansReponse(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/responsable/indexFiltreSansReponse.html.twig', [
            'questions' => $questionRepository->findBy(['Finished' => false], ['updateAt' => 'DESC'])
        ]);
    }

    #[Route('/question/Responsable/VosQuestion', name: 'app_responsable_concerner_index', methods: ['GET'])]
    public function ResponsableConcerner(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/responsable/indexQuestionResponsable.html.twig', [
            'questions' => $questionRepository->findBy(['Finished' => false], ['updateAt' => 'DESC'])
        ]);
    }

    #[Route('/question/Responsable/terminé', name: 'app_responsable_terminé', methods: ['GET'])]
    public function ResponsableTerminé(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/responsable/indexterminé.html.twig', [
            'questions' => $questionRepository->findBy([], ['updateAt' => 'DESC'])
        ]);
    }

    #[Route('/question/Repondue', name: 'app_question_repondue', methods: ['GET'])]
    public function Repondue(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/User/indexTerminé.html.twig', [
            'questions' => $questionRepository->findBy([], ['updateAt' => 'DESC'])
        ]);
    }

    #[Route('/question/EnCours', name: 'app_question_en_cours', methods: ['GET'])]
    public function Encours(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/User/indexEncours.html.twig', [
            'questions' => $questionRepository->findBy(['Finished' => false], ['DateQuest' => 'DESC'])
        ]);
    }

    #[Route('/question/index', name: 'app_question_indexG', methods: ['GET'])]
    public function IndexG(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/indexgeneral.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }

    #[Route('/question/new', name: 'app_question_new', methods: ['GET', 'POST'])]
    public function new(Request $request, QuestionRepository $questionRepository): Response
    {
        $question = new Question();
        $questId = $question->getQuestion();
        $question->setUser($this->getUser());

        if ($this->isGranted('ROLE_UAPP')) {
            # code...
            $form = $this->createForm(QuestionType::class, $question);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $questionRepository->add($question, true);
                    $this->addFlash('success', 'ajout avec succès');

                    return $this->redirectToRoute('app_question_en_cours', [], Response::HTTP_SEE_OTHER);
                } catch (\Exception $e) {
                    $this->addFlash('warning', 'erreur. Vérifier s"il vous plaît !');
                }
            } elseif ($form->isSubmitted() && !$form->isValid()) {
                $this->addFlash('error', 'Fausse manipulation');
            }
            return $this->renderForm('question/new.html.twig', [
                'question' => $question,
                'form' => $form,
                'questionId' => $questId,
            ]);
        } else {
            # code...
            $form = $this->createForm(QuestType::class, $question);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $questionRepository->add($question, true);
                    $this->addFlash('success', 'ajout avec succès');
                    return $this->redirectToRoute('app_question_en_cours', [], Response::HTTP_SEE_OTHER);
                } catch (\Exception $e) {
                    $this->addFlash('warning', 'erreur. Vérifier s"il vous plaît !');
                }
            } elseif ($form->isSubmitted() && !$form->isValid()) {
                $this->addFlash('error', 'Fausse manipulation');
            }
            return $this->renderForm('question/new2.html.twig', [
                'question' => $question,
                'form' => $form,
            ]);
        }
    }

    #[Route('/question/{id}/voir', name: 'app_question_show', methods: ['GET'])]
    public function show(Question $question): Response
    {
        $question->setUser($this->getUser());
        # code...
        return $this->render('question/responsable/show2.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/question/{id}/Responsable', name: 'app_question_showResponsable', methods: ['GET'])]
    public function showResponsable(Question $question, QuestionRepository $questionRepository): Response
    {
        # code...
        $question->setIsRead(true);
        $questionRepository->add($question, true);
        return $this->render('question/responsable/showResponsable.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/question/{id}', name: 'app_question_detail', methods: ['GET'])]
    public function Detail(Question $question): Response
    {

        # code...
        return $this->render('question/User/show.html.twig', [
            'question' => $question,
        ]);
    }

    // reponse du Résponsable
    #[Route('/question/{id}/solution', name: 'app_question_solution', methods: ['GET', 'POST'])]
    public function Solution(Request $request, Question $question, QuestionRepository $questionRepository, MailerInterface $mailer): Response
    {
        $question->setResponsable($this->getUser());
        $form = $this->createForm(ReponseType::class, $question);
        $form->handleRequest($request);
        $usermail = $this->getUser()->getMail();
        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $questionRepository->add($question, true);
                $email = (new TemplatedEmail())
                    ->from('testemailappsoi2@gmail.com')
                    ->to($usermail)
                    ->subject('AppSoi Gestion Incident')
                    ->html('<p>votre question avec la référence</p>' . $question->getId() . '<p>a été répondue</p>');

                $mailer->send($email);
                $this->addFlash('success', 'ajout avec succès');
                return $this->redirectToRoute('app_responsable_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('warning', 'erreur. Vérifier s"il vous plaît !');
            }
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Fausse manipulation');
        }

        return $this->renderForm('question/responsable/reponse.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    //Envoi vers autre responsable
    #[Route('/question/{id}/Cresponsable', name: 'app_question_Cresponsable', methods: ['GET', 'POST'])]
    public function AddResponsablereponse(Request $request, Question $question, QuestionRepository $questionRepository, MailerInterface $mailer): Response
    {

        $form = $this->createForm(ChoixResponsableType::class, $question);
        $form->handleRequest($request);
        $Rmail = $question->getResponsable($this->getUser())->getMail();
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $questionRepository->add($question, true);
                $email = (new TemplatedEmail())
                    ->from('testemailappsoi2@gmail.com')
                    ->to($Rmail)
                    ->subject('AppSoi Gestion Incident')
                    ->html('<p>Vous devez répondre au Question qui a la référence</p>' . $question->getId());

                $mailer->send($email);
                $this->addFlash('success', 'Envoyé');
                $this->addFlash('vous', 'Une question vous est adrresser');
                return $this->redirectToRoute('app_responsable_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('warning', 'erreur. Vérifier s"il vous plaît !');
            }
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Fausse manipulation');
        }

        return $this->renderForm('question/responsable/choixResponsable.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    //envoi de mail
    #[Route('/question/{id}/mail', name: 'app_responsable_mail', methods: ['GET', 'POST'])]
    public function QuestionMail(Request $request, MailerService $mailerService): Response
    {

        $form = $this->createForm(MailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $mailerService->sendEmail(
                from: $data['De'],
                to: $data['Email'],
                subject: $data['Question'],
                template: "emails/reponse.html.twig",
                parameters: [
                    "De" => $data['De'],
                    "Email" => $data['Email'],
                    "Question" => $data['Question'],
                ]
            );
            $this->addFlash('success', 'Envoyer');
            return $this->redirectToRoute('app_responsable_index', [], Response::HTTP_SEE_OTHER);
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Fausse manipulation');
        }

        return $this->renderForm('/question/responsable/mail.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/question/{id}/edit', name: 'app_question_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Question $question, QuestionRepository $questionRepository): Response
    {
        $question->setUser($this->getUser());
        $question->setIsRead(false);
        if ($this->isGranted('ROLE_UAPP')) {
            # code...

            $form = $this->createForm(QuestionType::class, $question);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $questionRepository->add($question, true);
                    $this->addFlash('success', 'modification avec succès');
                    return $this->redirectToRoute('app_question_en_cours', [], Response::HTTP_SEE_OTHER);
                } catch (\Exception $e) {
                    $this->addFlash('warning', 'il y a une fausse manipulation');
                }
            } elseif ($form->isSubmitted() && !$form->isValid()) {
                $this->addFlash('info', 'Fausse manipulation');
            }

            return $this->renderForm('question/edit.html.twig', [
                'question' => $question,
                'form' => $form,
            ]);
        } else {
            # code...

            $form = $this->createForm(QuestType::class, $question);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $questionRepository->add($question, true);
                    $this->addFlash('success', 'modification avec succès');
                    return $this->redirectToRoute('app_question_en_cours', [], Response::HTTP_SEE_OTHER);
                } catch (\Exception $e) {
                    $this->addFlash('warning', 'il y a une fausse manipulation');
                }
            } elseif ($form->isSubmitted() && !$form->isValid()) {
                $this->addFlash('info', 'Fausse manipulation');
            }

            return $this->renderForm('question/edit.html.twig', [
                'question' => $question,
                'form' => $form,
            ]);
        }
    }

    #[Route('/question/delete/{id}', name: 'app_question_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Question $question, QuestionRepository $questionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $question->getId(), $request->request->get('token'))) {
            $this->addFlash('error', 'il y a un erreur');
        } else {
            try {
                $questionRepository->remove($question, true);
                $this->addFlash('success', 'suppréssion avec succes');
            } catch (\Exception $e) {
                $this->addFlash('warning', 'ne peut pas être supprimer');
            }
        }
        return $this->redirectToRoute('app_responsable_terminé', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/question/delete/user/{id}', name: 'app_question_User_delete', methods: ['GET', 'POST'])]
    public function deleteUser(Request $request, Question $question, QuestionRepository $questionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $question->getId(), $request->request->get('token'))) {
            $this->addFlash('error', 'il y a un erreur');
        } else {
            try {
                $questionRepository->remove($question, true);
                $this->addFlash('success', 'suppréssion avec succes');
            } catch (\Exception $e) {
                $this->addFlash('warning', 'ne peut pas être supprimer');
            }
        }
        return $this->redirectToRoute('app_question_en_cours', [], Response::HTTP_SEE_OTHER);
    }

    // ajout du FAQ
    #[Route('/question/{id}/FAQ', name: 'app_question_FAQ', methods: ['GET', 'POST'])]
    public function FAQ(Request $request, Question $question, QuestionRepository $questionRepository): Response
    {
        $question->setResponsable($this->getUser());
        $question->setFAQ($question->getQuestion());
        $question->setSolution($question->getReponse());
        $form = $this->createForm(FaqType::class, $question);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $questionRepository->add($question, true);
                $this->addFlash('success', 'ajout avec succès');
                return $this->redirectToRoute('app_responsable_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('warning', 'erreur. Vérifier s"il vous plaît !');
            }
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Fausse manipulation');
        }

        return $this->renderForm('question/responsable/faq.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    // Modifier FAQ
    #[Route('/question/{id}/FAQ/Modifier', name: 'app_question_FAQ_Update', methods: ['GET', 'POST'])]
    public function FAQ_Edit(Request $request, Question $question, QuestionRepository $questionRepository): Response
    {
        $question->setResponsable($this->getUser());
        $question->setFAQ($question->getFAQ());
        $question->setSolution($question->getSolution());
        $form = $this->createForm(FaqType::class, $question);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $questionRepository->add($question, true);
                $this->addFlash('success', 'ajout avec succès');
                return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('warning', 'erreur. Vérifier s"il vous plaît !');
            }
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Fausse manipulation');
        }

        return $this->renderForm('question/responsable/faq_edit.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }
}
