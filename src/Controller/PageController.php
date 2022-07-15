<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/shitpost', name: 'app_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        return $this->render('index/index.html.twig', [
            'posts' => $entityManager->getRepository(Post::class)->getWithShitCount()
        ]);
    }

    #[Route('/shitpost/home', name: 'app_home')]
    public function home(EntityManagerInterface $entityManager): Response
    {
        $userId = $this->getUser()->getId();
        return $this->render('home/index.html.twig', [
            'posts' => $entityManager->getRepository(Post::class)->getWithShitCountShitted($userId)
        ]);
    }

    #[Route('/shitpost/myposts', name: 'app_my_posts')]
    public function myPosts(EntityManagerInterface $entityManager): Response
    {
        $userId = $this->getUser()->getId();
        return $this->render('my_posts/index.html.twig', [
            'posts' => $entityManager->getRepository(Post::class)->getUserPostsWithShitCountShitted($userId)
        ]);
    }

    #[Route('/shitpost/myshits', name: 'app_my_shits')]
    public function myShits(EntityManagerInterface $entityManager): Response
    {
        $userId = $this->getUser()->getId();
        return $this->render('my_shits/index.html.twig', [
            'posts' => $entityManager->getRepository(Post::class)->getUserShitsWithShitCountShitted($userId)
        ]);
    }
}
