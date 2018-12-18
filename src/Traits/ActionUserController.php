<?php

namespace App\Traits;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

trait ActionUserController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // recapcha
            $recapcha = $request->request->get('g-recaptcha-response');

            // password
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $role = $request->request->get('user')['role'];
            $user->addRole($role);

            if($role === "ROLE_USER") {
                $user->setGroupe(null);
            }

            $em = $this->getDoctrine()->getManager();

            $user->setUsername($user->getEmail());

            if($this->verifyRecapcha($recapcha) === true) {
                $em->persist($user);
                $em->flush();
            } else {
                $this->addFlash('error', "* Cochez la case recapcha");
        
                return $this->render('user/register.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            return $this->redirectToRoute('login');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    function verifyRecapcha($recaptcha){
            $url = "https://www.google.com/recaptcha/api/siteverify";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array(
                "secret"=>"6LenyYIUAAAAALvItGZIHLUiW90IIVBt918vDcKU","response"=>$recaptcha));
            $response = curl_exec($ch);
            curl_close($ch);
            $data = json_decode($response);     
        
        return $data->success;        
    }

    /**
     * @Route("/mon-compte", name="profile")
     */
    public function profile()
    {
        $candidacies = $this->getUser()->getCandidacies();

        return $this->render('user/profile.html.twig', [
            'candidacies' => $candidacies
        ]);
    }

    /**
     * @Route("/modifier/mon-compte", name="update_profile")
     */
    public function updateProfile(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user, ['no_role' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();

            $user->setUsername($user->getEmail());

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('profile');
        }

        return $this->render('user/update_account.html.twig', [
            'form' => $form->createView()
        ]);
    }
}