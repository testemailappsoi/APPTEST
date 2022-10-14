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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/question')]
class QuestionController extends AbstractController
{
    #[Route('/', name: 'app_question_index', methods: ['GET'])]
    public function index(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/User/indexSansReponse.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }

    #[Route('/Reponse', name: 'app_reponse_index', methods: ['GET', 'POST'])]
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

    #[Route('/Responsable/SansRep', name: 'app_responsable_index', methods: ['GET'])]
    public function ResponsableSansReponse(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/responsable/indexSansReponse.html.twig', [
            'questions' => $questionRepository->findBy(['Finished' => false], ['updateAt' => 'DESC'])
        ]);
    }

    #[Route('/Responsable/terminé', name: 'app_responsable_terminé', methods: ['GET'])]
    public function ResponsableTerminé(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/responsable/indexterminé.html.twig', [
            'questions' => $questionRepository->findBy([], ['updateAt' => 'DESC'])
        ]);
    }

    #[Route('/Repondue', name: 'app_question_repondue', methods: ['GET'])]
    public function Repondue(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/User/indexTerminé.html.twig', [
            'questions' => $questionRepository->findBy([], ['updateAt' => 'DESC'])
        ]);
    }

    #[Route('/EnCours', name: 'app_question_en_cours', methods: ['GET'])]
    public function Encours(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/User/indexEncours.html.twig', [
            'questions' => $questionRepository->findBy(['Finished' => false], ['DateQuest' => 'DESC'])
        ]);
    }

    #[Route('/index', name: 'app_question_indexG', methods: ['GET'])]
    public function IndexG(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/indexgeneral.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_question_new', methods: ['GET', 'POST'])]
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

    #[Route('/{id}/voir', name: 'app_question_show', methods: ['GET'])]
    public function show(Question $question): Response
    {
        $question->setUser($this->getUser());
        # code...
        return $this->render('question/responsable/show2.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/{id}/Responsable', name: 'app_question_showResponsable', methods: ['GET'])]
    public function showResponsable(Question $question, QuestionRepository $questionRepository): Response
    {
        # code...
        $question->setIsRead(true);
        $questionRepository->add($question, true);
        return $this->render('question/responsable/showResponsable.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/{id}', name: 'app_question_detail', methods: ['GET'])]
    public function Detail(Question $question): Response
    {

        # code...
        return $this->render('question/User/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/{id}/solution', name: 'app_question_solution', methods: ['GET', 'POST'])]
    public function Solution(Request $request, Question $question, QuestionRepository $questionRepository): Response
    {
        $question->setResponsable($this->getUser());
        $form = $this->createForm(ReponseType::class, $question);
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

        return $this->renderForm('question/responsable/reponse.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    //Envoi vers autre responsable
    #[Route('/{id}/Cresponsable', name: 'app_question_Cresponsable', methods: ['GET', 'POST'])]
    public function AddResponsablereponse(Request $request, Question $question, QuestionRepository $questionRepository): Response
    {
        
        $form = $this->createForm(ChoixResponsableType::class, $question);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $questionRepository->add($question, true);
                $this->addFlash('success', 'ajout avec succès');
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
    #[Route('/{id}/mail', name: 'app_responsable_mail', methods: ['GET', 'POST'])]
    public function QuestionMail(Request $request, Question $question, MailerInterface $mailer): Response
    {
        $question->setResponsable($this->getUser());
        $form = $this->createForm(MailType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
               //email
               $email = (new Email())
            ->from('testemailappsoi2@gmail.com')
            ->to('testemailappsoi1@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);
        $this->addFlash('success', 'Envoyer');
        // return $this->redirectToRoute('app_responsable_index', [], Response::HTTP_SEE_OTHER);
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Fausse manipulation');
        }

        return $this->renderForm('/question/responsable/mail.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_question_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Question $question, QuestionRepository $questionRepository): Response
    {
        $question->setUser($this->getUser());
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
    #[Route('/{id}/FAQ', name: 'app_question_FAQ', methods: ['GET', 'POST'])]
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
    #[Route('/{id}/FAQ/Modifier', name: 'app_question_FAQ_Update', methods: ['GET', 'POST'])]
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
