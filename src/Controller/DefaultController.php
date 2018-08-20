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
    

}

