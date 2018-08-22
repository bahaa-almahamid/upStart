<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Document;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\Post;

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
    public function profile(){
        $manager = $this->getDoctrine()->getManager();

        return $this->render(
            'profile/profile.html.twig',

            [
                'users' =>$manager->getRepository(User::class)->findBy(['username' => $this->getUser()->getId()]),
                ]

            );
            
    }
    

}

