<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Document;

class RegistrationController extends AbstractController
{
    public function registerUser(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {   

        $entityManager = $this->getDoctrine()->getManager();
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $this->captchaverify($request->get('g-recaptcha-response'))) {

            /**
             * @var UploadFile $file 
             * 
             */
            $file = $user->getPicture();
            if ($file) {
                $document = new Document();
                $document->setPath($this->getParameter('upload_dir'))
                         ->setMimeType($file->getMimeType())
                         ->setName($file->getFileName());
                $file->move($this->getParameter('upload_dir'));
                $user->setPicture($document);
                $entityManager->persist($document);
            }
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('post');
        }
        if($form->isSubmitted() &&  $form->isValid() && !$this->captchaverify($request->get('g-recaptcha-response'))){
                 
            $this->addFlash(
                'error',
                'Captcha Require'
            );}
        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView())
        );

    }

    function captchaverify($recaptcha){
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            "secret"=>"6LfolWoUAAAAAI0dVaJ4iIZgITSKc1kyAySxeokE","response"=>$recaptcha));
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);     
    
    return $data->success;        
}

    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
    
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
    
        return $this->render('post/index.html.twig', array(
            'username' => $username,
            'error'    => $error,
            'password' => $password
        ));
    }

    public function profile(){
        $manager = $this->getDoctrine()->getManager();

        
        

        return $this->redirectToRoute('profile',array("profile"=>$username->getId()(
        
              [
               'users' => $manager->getRepository(User::class)->findAll(),
              'posts' => $manager->getRepository(Post::class)->findAll(),

             ]
            )));
            
    }
}


