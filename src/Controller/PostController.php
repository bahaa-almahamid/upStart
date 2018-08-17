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
                'postForm' => $postForm->createView(),
                'searchForm' => $searchForm->createView()
            ]
        );

    }
    public function postDetail(post $post, Request $request)
    {
        $picture = $post->getPicture();
        if ($picture) {
            $post->setPicture(new File($picture->getPath().'/'.$picture->getName()));
        }
        $form = $this->createForm(PostFormType::class, $post, ['standalone' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_detail', ['post' => $post->getId()]);
         
        }


        return $this->render(
            'post/detail.html.twig',
            [
                'post' => $post,
                'form' => $form->createView()
            ]
        );
    }
}