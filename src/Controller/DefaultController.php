<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class DefaultController extends Controller
{
    public function homepage()
    {
        return $this->render('default/homepage.html.twig'); 
    }


}
