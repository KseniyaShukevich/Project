<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {

      /*  $question = new Question();
        $question->setText('How are you?');

        $answer1 = new Answer();

        $answer1->setText('Ok');
        $answer1->setIsCorrect(true);
        $answer1->setQuestion($question);

        $answer2 = new Answer();

        $answer2->setText('Bad');
        $answer2->setIsCorrect(false);
        $answer2->setQuestion($question);

        $this->em->persist($question);
        $this->em->persist($answer1);
        $this->em->persist($answer2);

        $this->em->flush();*/

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
