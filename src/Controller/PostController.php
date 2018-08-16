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
            return $this->redirectToRoute('post');
            if ($file) {

                $document = new Document();
                $document->setPath($this->getParameter('upload_dir'))
                    ->setMimeType($file->getMimeType())
                    ->setName($file->getFileName());

                $file->move($this->getParameter('upload_dir'));
                $post->setPicture($document);
                $manager->persist($document);
            }

          

            
        }
    
        $dto = new PostSearch();
        $searchForm = $this->createForm(PostSearchFormType::class, $dto, ['standalone' => true]);

        $searchForm->handleRequest($request);
        $posts = $manager->getRepository(Post::class)->findByPostSearch($dto);

        return $this->render(
            'post/index.html.twig',
            [
                'posts' => $post,
                'postForm' => $postForm->createView(),
                'searchForm' => $searchForm->createView()
            ]
        );

    }

}
