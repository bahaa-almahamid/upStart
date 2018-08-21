<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PostFormType;
use App\Entity\Post;
use App\Entity\Document;
use App\Form\PostSearchFormType;
use App\DTO\PostSearch;
use App\Entity\Comment;
use App\Form\CommentFormType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\User;


class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post")
     */
    public function postList(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $post = new Post();
        $postForm = $this->createForm(
            PostFormType::class,
            $post,
            ['standalone' => true]
        );
        $postForm->handleRequest($request);
        if ($postForm->isSubmitted() && $postForm->isValid()) {
            /**
             * @var UploadFile $file
             * 
             */

            $file = $post->getPicture();
            if ($file) {

                $document = new Document();
                $document->setPath($this->getParameter('upload_dir'))
                    ->setMimeType($file->getMimeType())
                    ->setName($file->getFileName());

                $file->move($this->getParameter('upload_dir'));
                $post->setPicture($document);
                $manager->persist($document);
            }

            $post->setUser($this->getUser());
            $manager->persist($post);
            $manager->flush();


            return $this->redirectToRoute('post');

        }
        
        //this is search function
        $dto = new PostSearch();
        $searchForm = $this->createForm(PostSearchFormType::class, $dto, ['standalone' => true]);

        $searchForm->handleRequest($request);
        $posts = $manager->getRepository(Post::class)->findByPostSearch($dto);
    
        return $this->render(
            'post/index.html.twig',
            [

                'posts' => $manager->getRepository(Post::class)->findAll(),
                'users' => $manager->getRepository(User::class)->findAll(),
                'postForm' => $postForm->createView(),
                'searchForm' => $searchForm->createView(),
            ]
        );

    
    }


    public function commentDetail(post $post, Request $request)
    {
        // for comment 
        $manager = $this->getDoctrine()->getManager();
        $comment = new Comment();
        $commentForm = $this->createForm(
            CommentFormType::class,
            $comment,
            ['standalone' => true]
        );
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            /**
             * @var UploadFile $file
             * 
             */

            $file = $comment->getPicture();
            if ($file) {

                $document = new Document();
                $document->setPath($this->getParameter('upload_dir'))
                    ->setMimeType($file->getMimeType())
                    ->setName($file->getFileName());

                $file->move($this->getParameter('upload_dir'));
                $comment->setPicture($document);
                $manager->persist($document);
            }
            
            $comment->setPost($post);
            $comment->setUser($this->getUser());
            $manager->persist($comment);
            $manager->flush();


            return $this->redirectToRoute('post_detail',array("post"=>$post->getId()));


        }

        return $this->render(
            'post/detail.html.twig',
            [                
                'post'=>$post,
                'commentForm' => $commentForm->createView(),
                'comments'=>$post->getComments(),

            ]
        );
    }
}

