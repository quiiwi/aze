<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Form\GroupeType;
use App\Repository\GroupeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @Route("/admin/groupe")
 */
class GroupeController extends AbstractController
{
    private function getStringResponse(Request $request)
    {
        $stringResponse = "";

        if($request->query->get('groupe') !== null and $request->query->get('groupe') !== "") {
            $stringResponse .= "?groupe[name]=" . $request->query->get('groupe')['name'];
            $stringResponse .= "&groupe[email]=" . $request->query->get('groupe')['email'];
            $stringResponse .= "&groupe[phone]=" . $request->query->get('groupe')['phone'];
            $stringResponse .= "&groupe[city]=" . $request->query->get('groupe')['city'];
        }

        return $stringResponse;
    }

    /**
     * @Route("/", name="groupe_index", methods="GET")
     */
    public function index(GroupeRepository $groupeRepository, Request $request): Response
    {
        $form = $this->createForm(GroupeType::class, null, ['method' => 'GET', 'filtering' => true]);
        $form->handleRequest($request);

        $groupes = $groupeRepository->search($request);
        $stringResponse = $this->getStringResponse($request);

        return $this->render('groupe/index.html.twig', [
            'entities' => $groupes,
            'stringResponse' => $stringResponse,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/export", name="groupe_export", methods="GET")
     */
    public function export(GroupeRepository $groupeRepository, Request $request)
    {
        $fileName = "liste_groupe_" . date("d_m_Y") . ".csv";
        $response = new StreamedResponse();
 
        $groupes = $groupeRepository->search($request, true)['results'];

        $response->setCallback(function() use ($groupes){
            $handle = fopen('php://output', 'w+');
 
            fputcsv($handle, [
                'Nom',
                'Email',
                utf8_decode('Téléphone'),
                'Ville',
            ], ';');
 
            foreach ($groupes as $groupe)
            {
                fputcsv($handle,array(
                    utf8_decode($groupe->getName()),
                    utf8_decode($groupe->getEmail()),
                    $groupe->getPhone(),
                    utf8_decode($groupe->getCity()),
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
     * @Route("/nouveau", name="groupe_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $groupe = new Groupe();
        return $this->newAndEdit($request, GroupeType::class, $groupe);
    }

    /**
     * @Route("/{id}/editer", name="groupe_edit", methods="GET|POST")
     */
    public function edit(Request $request, Groupe $groupe): Response
    {
        return $this->newAndEdit($request, GroupeType::class, $groupe);
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

            return $this->redirectToRoute('groupe_index');
        }

        return $this->render('groupe/edit.html.twig', [
            'groupe' => $entity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="groupe_show", methods="GET")
     */
    public function show(Groupe $groupe): Response
    {
        return $this->render('groupe/show.html.twig', ['groupe' => $groupe]);
    }

    /**
     * @Route("/{id}", name="groupe_delete", methods="DELETE")
     */
    public function delete(Request $request, Groupe $groupe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$groupe->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($groupe);
            $em->flush();
        }

        return $this->redirectToRoute('groupe_index');
    }
}