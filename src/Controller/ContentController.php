<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Content;
use App\Form\ContentType;
use App\Repository\ContentRepository;

/**
 * @Route("/admin/contenu")
 */
class ContentController extends AbstractController
{
    /**
     * @Route("/", name="content_index")
     */
    public function index(Request $request, ContentRepository $contentRepository)
    {
        $form = $this->createForm(ContentType::class, null, ['method' => 'GET', 'filtering' => true]);
        $form->handleRequest($request);

        $entities = $contentRepository->search($request);

        return $this->render('content/index.html.twig', [
        	'entities' => $entities,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/nouveau", name="content_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $content = new Content();
        return $this->newAndEdit($request, ContentType::class, $content);
    }

    /**
     * @Route("/{id}/editer", name="content_edit", methods="GET|POST")
     */
    public function edit(Request $request, Content $content): Response
    {
        return $this->newAndEdit($request, ContentType::class, $content);
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

            return $this->redirectToRoute('content_index');
        }

        return $this->render('content/edit.html.twig', [
            'content' => $entity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="content_show", methods="GET")
     */
    public function show(Content $content): Response
    {
        return $this->render('content/show.html.twig', ['content' => $content]);
    }

    /**
     * @Route("/{id}", name="content_delete", methods="DELETE")
     */
    public function delete(Request $request, Content $content): Response
    {
        if ($this->isCsrfTokenValid('delete'.$content->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($content);
            $em->flush();
        }

        return $this->redirectToRoute('content_index');
    }
}
