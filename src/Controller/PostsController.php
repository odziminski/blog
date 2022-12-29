<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @ApiResource
 */
class PostsController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/posts/json", name="posts_json", methods={"GET"})
     */
    public function getPostsToJSON(): Response
    {
        $posts = $this->entityManager->getRepository(Post::class)->findAll();

        return $this->json($posts);
    }
    /**
     * @Route("/lista", name="app_posts")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $posts = $em->getRepository(Post::class)->findBy([], ['id' => 'ASC']);
        return $this->render('posts/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post/delete/{id}", name="post_delete", methods={"POST"})
     */
    public function delete(Post $post): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();

        return $this->redirectToRoute('lista');
    }


    /**
     * @Route("/post/{id}", name="post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('posts/show.html.twig', [
            'post' => $post,
        ]);
    }

    public function homepage(){
        return $this->render('posts/default.html.twig');
    }

}
