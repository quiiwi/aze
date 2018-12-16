<?php

namespace App\Controller;

use App\Entity\Gir;
use App\Form\GirType;
use App\Repository\GirRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @Route("/admin/gir")
 */
class GirController extends AbstractController
{
    private function getStringResponse(Request $request)
    {
        $stringResponse = "";

        if($request->query->get('gir') !== null and $request->query->get('gir') !== "") {
            $stringResponse .= "?gir[one]=" . $request->query->get('gir')['one'];
            $stringResponse .= "&gir[two]=" . $request->query->get('gir')['two'];
            $stringResponse .= "&gir[three]=" . $request->query->get('gir')['three'];
        }

        return $stringResponse;
    }

    /**
     * @Route("/", name="gir_index", methods="GET")
     */
    public function index(GirRepository $girRepository, Request $request): Response
    {
        $form = $this->createForm(GirType::class, null, ['method' => 'GET', 'filtering' => true]);
        $form->handleRequest($request);

        $girs = $girRepository->search($request);
        $stringResponse = $this->getStringResponse($request);

        return $this->render('gir/index.html.twig', [
            'entities' => $girs,
            'stringResponse' => $stringResponse,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/export", name="gir_export", methods="GET")
     */
    public function export(GirRepository $girRepository, Request $request)
    {
        $fileName = "liste_gir_" . date("d_m_Y") . ".csv";
        $response = new StreamedResponse();
 
        $girs = $girRepository->search($request, true)['results'];

        $response->setCallback(function() use ($girs){
            $handle = fopen('php://output', 'w+');
 
            fputcsv($handle, [
                'GIR 1-2',
                'GIR 3-4',
                'GIR 5-6'
            ], ';');
 
            foreach ($girs as $gir)
            {
                fputcsv($handle,array(
                    utf8_decode($gir->getOne()),
                    utf8_decode($gir->getTwo()),
                    utf8_decode($gir->getThree())
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
     * @Route("/nouveau", name="gir_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $gir = new Gir();
        return $this->newAndEdit($request, GirType::class, $gir);
    }

    /**
     * @Route("/{id}/editer", name="gir_edit", methods="GET|POST")
     */
    public function edit(Request $request, Gir $gir): Response
    {
        return $this->newAndEdit($request, GirType::class, $gir);
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

            return $this->redirectToRoute('gir_index');
        }

        return $this->render('gir/edit.html.twig', [
            'gir' => $entity,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="gir_show", methods="GET")
     */
    public function show(Gir $gir): Response
    {
        return $this->render('gir/show.html.twig', ['gir' => $gir]);
    }

    /**
     * @Route("/{id}", name="gir_delete", methods="DELETE")
     */
    public function delete(Request $request, Gir $gir): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gir->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($gir);
            $em->flush();
        }

        return $this->redirectToRoute('gir_index');
    }
}
