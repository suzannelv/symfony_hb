<?php

namespace App\Controller;

use App\Entity\UserSecurity;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error]);
    }

    #[Route('/user/articles', name: 'app_user_articles')]
    public function showUserArticles(): Response
    {
        if($this->isGranted('ROLE_ADMIN'))
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user=$this->getUser();

        if(!$user instanceof UserSecurity){
            return $this->redirectToRoute('home');
        }
        $userArticles = $user->getArticle();

         return $this->render('security/userArticles.html.twig', [
                'articles'=> $userArticles
            ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


}
