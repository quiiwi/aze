<?php

namespace App\Controller;

use App\Form\UserAdminType;
use App\Form\UserAdminCreateType;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Traits\ActionUserController;

class UserController extends AbstractController
{
    use ActionUserController;

    private function getStringResponse(Request $request)
    {
        $stringResponse = "";

        if($request->query->get('user_admin') !== null and $request->query->get('user_admin') !== "") {
            $stringResponse .= "?user_admin[firstname]=" . $request->query->get('user_admin')['firstname'];
            $stringResponse .= "&user_admin[lastname]=" . $request->query->get('user_admin')['lastname'];
            $stringResponse .= "&user_admin[email]=" . $request->query->get('user_admin')['email'];
            $stringResponse .= "&user_admin[phone]=" . $request->query->get('user_admin')['phone'];
        }

        return $stringResponse;
    }

    /**
     * @Route("admin/user/", name="admin_user_index", methods="GET")
     */
    public function index(UserRepository $userRepository, Request $request): Response
    {
        $form = $this->createForm(UserAdminType::class, null, ['method' => 'GET', 'filtering' => true]);
        $form->handleRequest($request);

        $user = $userRepository->search($request);
        $stringResponse = $this->getStringResponse($request);

        return $this->render('user/index.html.twig', [
            'entities' => $user,
            'stringResponse' => $stringResponse,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/user/export", name="admin_user_export", methods="GET")
     */
    public function export(UserRepository $userRepository, Request $request)
    {
        $fileName = "liste_utilisateur_" . date("d_m_Y") . ".csv";
        $response = new StreamedResponse();

        $users = $userRepository->search($request, true)['results'];

        $response->setCallback(function() use ($users){
            $handle = fopen('php://output', 'w+');
 
            fputcsv($handle, [
                'Genre',
                'Nom',
                'Prénom',
                utf8_decode('Email'),
                'Date de naissance',
                'Téléphone',
                'Addresse',
                'Code postal',
                utf8_decode('Ville'),
                'Région',
                'Département',
            ], ';');
 
            foreach ($users as $user)
            {
                $date = "";
                $gender = "";

                if($user->getGender() === 'woman') {
                    $gender = "Femme";
                } else {
                    $gender = "Homme";
                }

                $date = $user->getBirthday()->format('d/m/Y');

                fputcsv($handle,array(
                    $gender,
                    utf8_decode($user->getLastname()),
                    utf8_decode($user->getFirstname()),
                    utf8_decode($user->getEmail()),
                    $date,
                    $user->getPhone(),
                    $user->getAddress(),
                    $user->getZipCode(),
                    $user->getCity(),
                    $user->getRegion(),
                    $user->getDepartment()
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
     * @Route("/admin/user/nouveau", name="admin_user_new", methods="GET|POST")
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        return $this->newAndEdit($request, UserAdminCreateType::class, $user, $passwordEncoder);
    }

    /**
     * @Route("/admin/user/{id}/editer", name="admin_user_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        return $this->newAndEdit($request, UserAdminCreateType::class, $user, $passwordEncoder);
    }

    private function newAndEdit($request, $form, $entity, $passwordEncoder)
    {
        $entity = $entity;
        $form = $this->createForm($form, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUsername($entity->getEmail());

            $password = $passwordEncoder->encodePassword($entity, $entity->getPassword());
            $entity->setPassword($password);

            $role = $request->request->get('user_admin_create')['role'];
            $entity->addRole($role);

            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $entity,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/user/{id}", name="admin_user_show", methods="GET")
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/admin/user/{id}", name="admin_user_delete", methods="DELETE")
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('admin_user_index');
    }
}