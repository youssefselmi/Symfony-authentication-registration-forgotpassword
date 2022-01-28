<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\Question;
use App\Repository\EntrainementRepository;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TemplateFrontController extends AbstractController
{
    /**
     * @Route("/template/front", name="template_front")
     */
    public function index(): Response
    {
        return $this->render('template_front/index2.html.twig', [
            'controller_name' => 'TemplateFrontController',
        ]);
    }

    /**
     * @Route("/template/stat", name="template_front_stat")
     */
    public function question_stat(QuestionRepository $questionRepository): Response
    {
       /* return $this->render('template_front/question_stat.html.twig', [
            'controller_name' => 'TemplateFrontController',
        ]);
*/

        return $this->render('template_front/question_stat.html.twig', [
            'controller_name' => 'TemplatefrontController',
            'Question' => $questionRepository->createQueryBuilder('u')->select('u')->getQuery()->getResult()

        ]);
    }





    /**
     * @Route("/{id}/templatefrontdetailsquestion", name="templatefrontdetailsquestion")
     */
    public function detailjunior(Question $question, $id): Response
    {
        return $this->render('template_front/quiz.html.twig', [
            'controller_name' => 'TemplatefrontController',
            'question' => $question
        ]);
    }










}
