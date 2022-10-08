<?php

namespace App\Controller;

use App\Repository\DailyValuationRepository;
use App\Service\ChartGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChartController extends AbstractController
{
    #[Route('/graphique', name: 'chart')]
    public function chart(ChartGenerator $chartGenerator,DailyValuationRepository $dailyValuationRepository): Response
    {
        $dailyValuations = $dailyValuationRepository->findBy([],[
            'createdAt' => 'ASC'
        ]);

        $chart = $chartGenerator->getChart($dailyValuations);

        return $this->render("chart.html.twig",[
            'chart' => $chart
        ]);
    }
}