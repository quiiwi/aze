<?php

namespace App\Controller;

use App\Entity\Candidacy;
use App\Form\CandidacyType;
use App\Form\CandidacyCreateType;
use App\Repository\CandidacyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @Route("/admin/candidacy")
 */
class CandidacyController extends AbstractController
{
    private function getStringResponse(Request $request)
    {
        $stringResponse = "";

        if($request->query->get('candidacy') !== null and $request->query->get('candidacy') !== "") {
            $stringResponse .= "?candidacy[firstname]=" . $request->query->get('candidacy')['firstname'];
            $stringResponse .= "&candidacy[lastname]=" . $request->query->get('candidacy')['lastname'];
            $stringResponse .= "&candidacy[email]=" . $request->query->get('candidacy')['email'];
            $stringResponse .= "&candidacy[status]=" . $request->query->get('candidacy')['status'];
            $stringResponse .= "&candidacy[establishment]=" . $request->query->get('candidacy')['establishment'];
        }

        return $stringResponse;
    }

    /**
     * @Route("/", name="candidacy_index", methods="GET")
     */
    public function index(CandidacyRepository $candidacyRepository, Request $request): Response
    {
        $form = $this->createForm(CandidacyType::class, null, ['method' => 'GET', 'filtering' => true]);
        $form->handleRequest($request);

        $candidacies = $candidacyRepository->search($request);
        $stringResponse = $this->getStringResponse($request);

        return $this->render('candidacy/index.html.twig', [
            'entities' => $candidacies,
            'stringResponse' => $stringResponse,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/export", name="candidacy_export", methods="GET")
     */
    public function export(CandidacyRepository $candidacyRepository, Request $request)
    {
        $fileName = "liste_candidature_" . date("d_m_Y") . ".csv";
        $response = new StreamedResponse();

        $candidacies = $candidacyRepository->search($request, true)['results'];

        $response->setCallback(function() use ($candidacies){
            $handle = fopen('php://output', 'w+');
 
            fputcsv($handle, [
                'Nom',
                utf8_decode('Prénom'),
                'Email',
                'Status',
                'Etablissement'
            ], ';');
 
            foreach ($candidacies as $candidacy)
            {
                fputcsv($handle,array(
                    utf8_decode($candidacy->getUser()->getLastname()),
                    utf8_decode($candidacy->getUser()->getFirstname()),
                    utf8_decode($candidacy->getUser()->getEmail()),
                    utf8_decode($candidacy->getStatus()),
                    utf8_decode($candidacy->getEstablishment())
                ),';');
            }
            fclose($handle);
        });
 
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8', 'application/force-download');
        $response->headers->set('Content-Disposition','attachment; filename='.$fileName);
        
        return $response;
    }

    /**
     * @Route("/nouveau", name="candidacy_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $candidacy = new Candidacy();
        return $this->newAndEdit($request, CandidacyCreateType::class, $candidacy);
    }

    /**
     * @Route("/{id}/editer", name="candidacy_edit", methods="GET|POST")
     */
    public function edit(Request $request, Candidacy $candidacy): Response
    {
        return $this->newAndEdit($request, CandidacyCreateType::class, $candidacy);
    }

    private function newAndEdit($request, $form, $entity)
    {
        $entity = $entity;
        $form = $this->createForm($form, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('candidacy_index');
        }

        return $this->render('candidacy/edit.html.twig', [
            'candidacy' => $entity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="candidacy_show", methods="GET")
     */
    public function show(Candidacy $candidacy): Response
    {
        return $this->render('candidacy/show.html.twig', ['candidacy' => $candidacy]);
    }

    /**
     * @Route("/{id}", name="candidacy_delete", methods="DELETE")
     */
    public function delete(Request $request, Candidacy $candidacy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$candidacy->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($candidacy);
            $em->flush();
        }

        return $this->redirectToRoute('candidacy_index');
    }
}
