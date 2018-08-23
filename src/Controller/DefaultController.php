<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Document;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\Post;
use App\Form\UserFormType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Form\ProfileEditFormType;

class DefaultController extends Controller
{
    public function homepage()
    {
        return $this->render('default/homepage.html.twig');
    }

    public function aboutUs()
    {
        return $this->render('default/about.html.twig'); 
    }

    public function termsOfUse()
    {
        return $this->render('default/terms.html.twig'); 
    }

    public function privacyPolicy()
    {
        return $this->render('default/privacy.html.twig'); 
    }

    public function contactUs()
    {
        return $this->render('default/contact.html.twig'); 
    }

    public function downloadDocument(Document $document)
    {
        $fileName = sprintf(
            '%s/%s',
            $document->getPath(),
            $document->getName()
        );
        return new BinaryFileResponse($fileName);
    }


    public function profile()
    {
        $manager = $this->getDoctrine()->getManager();

        return $this->render(
            'profile/profile.html.twig',

            [
                'user' => $this->getUser(),
            ]

        );

    }
    // Edit Profile /*********************** */
    public function profileEdit(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $picture = $user->getPicture();
        if($picture)
        {
            $file = new File($picture->getPath() . '/' . $picture->getName());
            $user->setPicture($file);
        }

        $profileForm = $this->createForm(ProfileEditFormType::class, $user, ['standalone' => true]);
        $profileForm->handleRequest($request);
        
        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            
            $file = $user->getPicture();
            if($file){
                $document = new Document();
                $document->setPath($this->getParameter('upload_dir'))
                    ->setMimeType($file->getMimeType())
                    ->setName($file->getFilename());
                $file->move($this->getParameter('upload_dir'));
                
                $user->setPicture($document);
                
                $manager->persist($document);
                $manager->remove($picture);
            }
            else
            {
                $user->setPicture($picture);
            }

            $manager->flush();
            
            return $this->redirectToRoute('profile');
        }

        $user->setPicture($picture);
        
        return $this->render(
            'profile/profileEdit.html.twig',
            [
                'user'=>$user,
                'profileForm' => $profileForm->createView()
            ]
        );
    }



}

