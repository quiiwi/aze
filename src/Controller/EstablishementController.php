<?php

namespace App\Controller;

use App\Entity\Establishement;
use App\Form\EstablishementType;
use App\Form\EstablishementCreateType;
use App\Repository\EstablishementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @Route("/admin/establishement")
 */
class EstablishementController extends AbstractController
{
    private function getStringResponse(Request $request)
    {
        $stringResponse = "";

        if($request->query->get('establishement') !== null and $request->query->get('establishement') !== "") {
            $stringResponse .= "?establishement[name]=" . $request->query->get('establishement')['name'];
            $stringResponse .= "&establishement[city]=" . $request->query->get('establishement')['city'];
            $stringResponse .= "&establishement[type]=" . $request->query->get('establishement')['type'];
            $stringResponse .= "&establishement[status]=" . $request->query->get('establishement')['status'];
        }

        return $stringResponse;
    }

    /**
     * @Route("/", name="establishement_index", methods="GET")
     */
    public function index(EstablishementRepository $establishementRepository, Request $request): Response
    {
        $form = $this->createForm(EstablishementType::class, null, ['method' => 'GET', 'filtering' => true]);
        $form->handleRequest($request);

        $establishement = $establishementRepository->search($request);
        $stringResponse = $this->getStringResponse($request);

        return $this->render('establishement/index.html.twig', [
            'entities' => $establishement,
            'stringResponse' => $stringResponse,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/export", name="establishement_export", methods="GET")
     */
    public function export(EstablishementRepository $establishementRepository, Request $request)
    {
        $fileName = "liste_etablissement_" . date("d_m_Y") . ".csv";
        $response = new StreamedResponse();

        $establishements = $establishementRepository->search($request, true)['results'];

        $response->setCallback(function() use ($establishements){
            $handle = fopen('php://output', 'w+');
 
            fputcsv($handle, [
                'Nom',
                'Email',
                'Type',
                utf8_decode('Téléphone'),
                'Addresse',
                'Code postal',
                'Ville',
                'Département',
                utf8_decode('Région'),
                'Note',
                'Status',
                'Groupe',
                'GIR'
            ], ';');
 
            foreach ($establishements as $establishement)
            {
                fputcsv($handle,array(
                    utf8_decode($establishement->getName()),
                    utf8_decode($establishement->getEmail()),
                    utf8_decode($establishement->getType()),
                    $establishement->getTelephon(),
                    utf8_decode($establishement->getAddress()),
                    $establishement->getZipCode(),
                    utf8_decode($establishement->getCity()),
                    utf8_decode($establishement->getDepartment()),
                    utf8_decode($establishement->getRegion()),
                    $establishement->getNotation(),
                    utf8_decode($establishement->getStatus()),
                    utf8_decode($establishement->getGroupe()),
                    utf8_decode($establishement->getGir()),
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
     * @Route("/nouveau", name="establishement_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $establishement = new Establishement();
        return $this->newAndEdit($request, EstablishementCreateType::class, $establishement);
    }

    /**
     * @Route("/{id}/editer", name="establishement_edit", methods="GET|POST")
     */
    public function edit(Request $request, Establishement $establishement): Response
    {
        return $this->newAndEdit($request, EstablishementCreateType::class, $establishement);
    }

    private function newAndEdit($request, $form, $entity)
    {
        $entity = $entity;
        $form = $this->createForm($form, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            foreach($entity->getServices() as $s) {
                $entity->addService($s);
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('establishement_index');
        }

        return $this->render('establishement/edit.html.twig', [
            'establishement' => $entity,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="establishement_show", methods="GET")
     */
    public function show(Establishement $establishement): Response
    {
        return $this->render('establishement/show.html.twig', ['establishement' => $establishement]);
    }

    /**
     * @Route("/{id}", name="establishement_delete", methods="DELETE")
     */
    public function delete(Request $request, Establishement $establishement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$establishement->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($establishement);
            $em->flush();
        }

        return $this->redirectToRoute('establishement_index');
    }
}
