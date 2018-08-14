<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Role;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class DefaultController extends Controller
{
    public function homepage()
    {
        return $this->render('default/homepage.html.twig'); //
    }

    public function login(AuthenticationUtils $authenticationUtils)
    {
            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
        
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
        
            return $this->render('default/login.html.twig', array(
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    // public function downloadDocument(Document $document)
    // {
    //     $filename = sprintf(
    //         '%s/%s',
    //         $document->getPath(),
    //         $document->getName()
    //     );

    //     return new BinaryFileResponse($filename);
    // }
}
