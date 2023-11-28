<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        
        return $this->render('index/index.html.twig', [
            'title' => 'Mon Blog',
        
        ]);
      
    }
    // ou bien au lieu de créer un nouveau controller, on peut créer sous la même classe controller
    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('index/about.html.twig', [
            'title' => 'A propos',
        ]);
    }
}
