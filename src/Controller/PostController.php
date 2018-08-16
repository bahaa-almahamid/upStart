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
            $manager->persist($post);
            $manager->flush();
            
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


            return $this->redirectToRoute('post');


        }
        //this is search function
        $dto = new PostSearch();
        $searchForm = $this->createForm(PostSearchFormType::class, $dto, ['standalone' => true]);

        $searchForm->handleRequest($request);
        $posts = $manager->getRepository(Post::class)->findByPostSearch($dto);

        //this is for comments
        $comment = new Comment();
        $commentForm = $this->createForm(
            CommentFormType::class,
            $comment,
            ['standalone' => true]
        );
             if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                /**
                 * @var UploadFile $file
                 * 
                 */
                $manager->persist($comment);
                $manager->flush();
                $file = $comment->getPicture();
                return $this->redirectToRoute('comment');
                if ($file) 
                {
    
                    $document = new Document();
                    $document->setPath($this->getParameter('upload_dir'))
                        ->setMimeType($file->getMimeType())
                        ->setName($file->getFileName());
    
                    $file->move($this->getParameter('upload_dir'));
                    $comment->setPicture($document);
                    $manager->persist($document);
                }
    

            }
        $posts = $manager->getRepository(Post::class)->listComment($comment);

        return $this->render(
            'post/index.html.twig',
            [

                'posts' => $manager->getRepository(Post::class)->findAll(),

                'postForm' => $postForm->createView(),
                'searchForm' => $searchForm->createView(),
                'commentForm' => $commentForm->createView()
            ]
        );

    }
}
