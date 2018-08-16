<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Chat;
use App\Form\ChatFormType;
use Symfony\Component\HttpFoundation\Request;

class ChatController extends AbstractController
{
    /**
     * @Route("/chat", name="chat")
     */
    public function chatList(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $project = new Chat();
        $projectForm = $this->createForm(ChatFormType::class, $project, ['standalone' => true]);
        
        $projectForm->handleRequest($request);
        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            $manager->persist($project);
            $manager->flush();
            
            return $this->redirectToRoute('chat');
        }

        return $this->render('chat/index.html.twig', [
            'Chats' => $manager->getRepository(Chat::class)->findAll(),
            'projectForm' => $projectForm->createView()
        ]);
    }
}
