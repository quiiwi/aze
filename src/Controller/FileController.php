<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FileType;
use App\Entity\File;

class FileController extends AbstractController
{
    /**
     * @Route("/mon-compte/file", name="user_file")
     */
    public function index(Request $request)
    {
		$user = $this->getUser();
    	$file = new File();
    	$form = $this->createForm(FileType::class, $file);
    	$form->handleRequest($request);

    	if($form->isSubmitted() and $form->isValid()) {

    		$em = $this->getDoctrine()->getManager();

    		$document = $request->files->get('file')['document'];

    		$fileName = $user->getId() . '_' . $this->generateUniqueFileName().'.'.$document->guessExtension();

    		$document->move($this->getParameter('document_directory'), $fileName);

    		$file->setDocument($fileName);
    		$file->setUser($user);

    		$em->persist($file);
    		$em->flush();

			return $this->redirect($this->generateUrl('user_file'));
    	}

        return $this->render('file/index.html.twig', [
        	'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/mon-compte/delete/file/{filename}", name="user_file_delete")
     */
    public function delete(Request $request, $filename)
    {
        $em = $this->getDoctrine()->getManager();

        $document = $em->getRepository(File::class)->findOneByDocument($filename);

        if($document === null) {
            throw new \Exception("Le fichier " . $filename ." n'existe pas");
        }

        $fileToRemove = $this->getParameter('document_directory') . '/' . $filename;
        unlink($fileToRemove);

        $em->remove($document);
        $em->flush();

        return $this->redirect($this->generateUrl('user_file'));
    }

    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}
