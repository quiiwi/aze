<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @Route("/admin/service")
 */
class ServiceController extends AbstractController
{
    private function getStringResponse(Request $request)
    {
        $stringResponse = "";

        if($request->query->get('service') !== null and $request->query->get('service') !== "") {
            $stringResponse .= "?service[name]=" . $request->query->get('service')['name'];
        }

        return $stringResponse;
    }

    /**
     * @Route("/", name="service_index", methods="GET")
     */
    public function index(ServiceRepository $serviceRepository, Request $request): Response
    {
        $form = $this->createForm(ServiceType::class, null, ['method' => 'GET']);
        $form->handleRequest($request);

        $services = $serviceRepository->search($request);
        $stringResponse = $this->getStringResponse($request);

        return $this->render('service/index.html.twig', [
            'entities' => $services,
            'stringResponse' => $stringResponse,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/export", name="service_export", methods="GET")
     */
    public function export(ServiceRepository $serviceRepository, Request $request)
    {
        $fileName = "liste_service_" . date("d_m_Y") . ".csv";
        $response = new StreamedResponse();
 
        $services = $serviceRepository->search($request, true)['results'];

        $response->setCallback(function() use ($services){
            $handle = fopen('php://output', 'w+');
 
            fputcsv($handle, [
                'Nom'
            ], ';');
 
            foreach ($services as $service)
            {
                fputcsv($handle,array(
                    utf8_decode($service->getName())
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
     * @Route("/nouveau", name="service_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $service = new Service();
        return $this->newAndEdit($request, ServiceType::class, $service);
    }

    /**
     * @Route("/{id}/editer", name="service_edit", methods="GET|POST")
     */
    public function edit(Request $request, Service $service): Response
    {
        return $this->newAndEdit($request, ServiceType::class, $service);
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

            return $this->redirectToRoute('service_index');
        }

        return $this->render('service/edit.html.twig', [
            'service' => $entity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="service_show", methods="GET")
     */
    public function show(Service $service): Response
    {
        return $this->render('service/show.html.twig', ['service' => $service]);
    }

    /**
     * @Route("/{id}", name="service_delete", methods="DELETE")
     */
    public function delete(Request $request, Service $service): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($service);
            $em->flush();
        }

        return $this->redirectToRoute('service_index');
    }
}