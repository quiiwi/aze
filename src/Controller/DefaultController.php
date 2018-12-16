<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Candidacy;
use App\Entity\Establishement;
use App\Entity\Service;
use App\Entity\Content;

class DefaultController extends AbstractController
{
    /**
     * @Route("/fiche-client", name="fiche-client")
     */
    public function ficheClient()
    {
        return $this->render('client/fiche.html.twig');
    }
    
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $notes = $em->getRepository('App:Note')->findBy([
            'isVisible' => true
        ], ['notation' => 'DESC'], 3);

        $countNotes = count($em->getRepository('App:Note')->findAll());

        $establishments = $em->getRepository('App:Establishement')->findBy([
            'status' => 'Approuvé',
        ], ['notation' => 'DESC'], 3);

        $content = $em->getRepository('App:Content')->findOneByName("Contenu page d'accueil");

        $countEstablishment = count($em->getRepository('App:Establishement')->findAll());
        $countCandidacy = count($em->getRepository('App:Candidacy')->findAll());

        return $this->render('default/index.html.twig', [
            'notes' => $notes,
            'countNotes' => $countNotes,
            'establishments' => $establishments,
            'countEstablishment' => $countEstablishment,
            'countCandidacy' => $countCandidacy,
            'content' => $content
        ]);
    }

    /**
     * @Route("/recherche", name="search")
     */
    public function search(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $services = $em->getRepository(Service::class)->findAll();

        $establishments = [];

        if (count($request->query) > 0) {
            $establishments = $em->getRepository(Establishement::class)->search($request);
        } else {
            $establishments = $em->getRepository('App:Establishement')->findByStatus("Approuvé");
        }

        return $this->render('default/search.html.twig', [
            'establishments' => $establishments,
            'services' => $services,
            'name' => $request->query->get('name'),
            'place' => $request->query->get('place')
        ]);
    }

    /**
     * @Route("/faq", name="faq")
     */
    public function faq()
    {
        return $this->render('default/faq.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('default/contact.html.twig');
    }

    /**
     * @Route("/candidacy/send", name="candidacy_send")
     */
    public function candidacySend(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ids = $request->request->get('ids');

        foreach($ids as $id) {
            $establishment = $em->getRepository('App:Establishement')->findOneById($id);

            $candidacy = $em->getRepository('App:Candidacy')->findBy([
                'user' => $this->getUser(),
                'establishment' => $establishment
            ]);

            if(empty($candidacy)) {
                $candidacy = new Candidacy();
                $candidacy->setEstablishment($establishment);
                $candidacy->setUser($this->getUser());
                $candidacy->setStatus("Nouvelle");

                $em->persist($candidacy);
            }
        }

        $em->flush();

        return new Response("OK");
    }

}
