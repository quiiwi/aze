<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @Route("/admin/note")
 */
class NoteController extends AbstractController
{
    private function getStringResponse(Request $request)
    {
        $stringResponse = "";

        if($request->query->get('note') !== null and $request->query->get('note') !== "") {
            $stringResponse .= "?note[name]=" . $request->query->get('note')['name'];
            $stringResponse .= "&note[place]=" . $request->query->get('note')['place'];
            $stringResponse .= "&note[notation]=" . $request->query->get('note')['notation'];
            $stringResponse .= "&note[commentary]=" . $request->query->get('note')['commentary'];
            $stringResponse .= "&note[isVisible]=" . $request->query->get('note')['isVisible'];
        }

        return $stringResponse;
    }

    /**
     * @Route("/", name="note_index", methods="GET")
     */
    public function index(NoteRepository $noteRepository, Request $request): Response
    {
        $form = $this->createForm(NoteType::class, null, ['method' => 'GET', 'filtering' => true]);
        $form->handleRequest($request);

        $notes = $noteRepository->search($request);
        $stringResponse = $this->getStringResponse($request);

        return $this->render('note/index.html.twig', [
            'entities' => $notes,
            'stringResponse' => $stringResponse,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/export", name="note_export", methods="GET")
     */
    public function export(NoteRepository $noteRepository, Request $request)
    {
        $fileName = "liste_avis_" . date("d_m_Y") . ".csv";
        $response = new StreamedResponse();
 
        $notes = $noteRepository->search($request, true)['results'];

        $response->setCallback(function() use ($notes){
            $handle = fopen('php://output', 'w+');
 
            fputcsv($handle, [
                'Nom',
                'Place',
                'Note',
                'Commentaire',
                'Visible'
            ], ';');
 
            foreach ($notes as $note)
            {
                $visible = 'Non';
                if($note->getIsVisible()) {
                    $visible = 'Oui';
                }

                fputcsv($handle,array(
                    utf8_decode($note->getName()),
                    utf8_decode($note->getPlace()),
                    $note->getNotation(),
                    utf8_decode($note->getCommentary()),
                    $visible
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
     * @Route("/nouveau", name="note_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $note = new Note();
        return $this->newAndEdit($request, NoteType::class, $note);
    }

    /**
     * @Route("/{id}/editer", name="note_edit", methods="GET|POST")
     */
    public function edit(Request $request, Note $note): Response
    {
        return $this->newAndEdit($request, NoteType::class, $note);
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

            return $this->redirectToRoute('note_index');
        }

        return $this->render('note/edit.html.twig', [
            'note' => $entity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="note_show", methods="GET")
     */
    public function show(Note $note): Response
    {
        return $this->render('note/show.html.twig', ['note' => $note]);
    }

    /**
     * @Route("/{id}", name="note_delete", methods="DELETE")
     */
    public function delete(Request $request, Note $note): Response
    {
        if ($this->isCsrfTokenValid('delete'.$note->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($note);
            $em->flush();
        }

        return $this->redirectToRoute('note_index');
    }
}