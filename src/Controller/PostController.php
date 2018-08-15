<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PostFormType;
use App\Entity\Post;
use App\Entity\Document;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post")
     */
    public function postList(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $post = new Post();
        $postForm = $this->createForm(PostFormType::class,
        $post,['standalone' => true]);
        $postForm->handleRequest($request);
        if ($postForm->isSubmitted() && $postForm->isValid()) {
            /**
             * @var UploadFile $file
             */
            $file = $post->getPicture();
            if($file)
            {
                
                $document = new Document();
                $document->setPath($this->getParameter('upload_dir'))
                    ->setMimeType($file->getMimeType())
                    ->setName($file->getFileName());

                $file->move($this->getParameter('upload_dir'));
                $post->setPicture($document);
                $manager->persist($document);
            }

            $manager->persist($post);
            $manager->flush();
            
            return $this->redirectToRoute('post');
        }

        $postForm->handleRequest($request);
        return $this->render('post/index.html.twig', [
            'posts' => $manager->getRepository(Post::class)->findAll(),
                'postForm' => $postForm->createView()
                ]);
    }

        public function commentList(Request $request)
        {
            $manager = $this->getDoctrine()->getManager();
            $comment = new Comment();
            $commentForm = $this->createForm(CommentFormType::class,
            $comment,['standalone' => true]);
            $commentForm->handleRequest($request);
            if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                /**
                 * @var UploadFile $file
                 */
                $file = $comment->getPicture();
                if($file)
                {
                    
                    $document = new Document();
                    $document->setPath($this->getParameter('upload_dir'))
                        ->setMimeType($file->getMimeType())
                        ->setName($file->getFileName());
    
                    $file->move($this->getParameter('upload_dir'));
                    $comment->setPicture($document);
                    $manager->persist($document);
                }
    
                $manager->persist($comment);
                $manager->flush();
                
                return $this->redirectToRoute('post');
            }
    
    
    
            $commentForm->handleRequest($request);
            
        


        
        return $this->render('post/index.html.twig', [
            'comments' => $manager->getRepository(Comment::class)->findAll(),
                'commentForm' => $commentForm->createView()
        ]);
    }
}
