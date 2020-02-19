<?php

namespace App\Controller;

use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class KalkulatorController extends AbstractController
{
    /**
     * @Route("/kalkulator", name="kalkulator")
     */
    public function index()
    {
        return $this->render('kalkulator/index.html.twig', [
            'controller_name' => 'KalkulatorController',
        ]);
    }
}
