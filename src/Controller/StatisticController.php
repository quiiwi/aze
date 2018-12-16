<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Candidacy;

class StatisticController extends AbstractController
{
    /**
     * @Route("/statistic", name="statistic_index")
     */
    public function index()
    {
    	$em = $this->getDoctrine()->getManager();

    	$countCandidacy = count($em->getRepository(Candidacy::class)->findAll());

    	$countCandidacyAccept = count($em->getRepository(Candidacy::class)->findBy([
    		'status' => 'Accepté'
    	]));

    	$countCandidacyRefused = count($em->getRepository(Candidacy::class)->findBy([
    		'status' => 'Refusé'
    	]));

    	$countCandidacyNew = count($em->getRepository(Candidacy::class)->findBy([
    		'status' => 'Nouvelle'
    	]));

        return $this->render('statistic/index.html.twig', [
        	'countCandidacy' => $countCandidacy,
        	'countCandidacyAccept' => $countCandidacyAccept,
        	'countCandidacyRefused' => $countCandidacyRefused,
        	'countCandidacyNew' => $countCandidacyNew
        ]);
    }
}
