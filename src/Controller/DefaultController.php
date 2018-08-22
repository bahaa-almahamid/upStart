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
                'user' =>$this->getUser(),
                ]
            );          
    }

     public function about()
    {
        return $this->render('Default/about.html.twig'); 
    }

    public function tamara()
    {
        return $this->render('members/tamara.html.twig'); 
    }

    public function miro()
    {
        return $this->render('members/miro.html.twig'); 
    }

    public function ibrahem()
    {
        return $this->render('members/ibrahem.html.twig'); 
    }

    public function bahaa()
    {
        return $this->render('members/bahaa.html.twig'); 
    }

    public function joao()
    {
        return $this->render('members/joao.html.twig'); 
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
    
    public function contact()
    {
        return $this->render('default/contact.html.twig'); 
    }


}

