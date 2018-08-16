<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function homepage()
    {
        return $this->render('default/homepage.html.twig'); 
    }
    
    public function profile()
    {
        return $this->render('profile/profile.html.twig'); 
    }
}