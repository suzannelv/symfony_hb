<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Form\CategoryType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
{

    
    #[Route('/articles', name: 'articles')]
    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    { 

        $articles = $paginator->paginate(
            $articleRepository->findAll(),
            $request->query->getInt('page', 1), 
            10 
        );
        // var_dump($articleRepository->findAll());
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles,
        ]);
       
    }


// solution 1:
    // #[Route('/articles/{id}', name: 'item')]
    // public function article(ArticleRepository $articleRepository, int $id): Response
    // {
    //     $article = $articleRepository->find($id);

    //     if($article===null){
    //         throw new NotFoundHttpException("article no trouvé");
    //     }
    //     return $this->render('article/item.html.twig', [
    //         'article' => $article
    //     ]);
    // }

// solution 2:
    // /articles/item?id=12
    // #[Route('articles/item', name: 'item')]
    // public function item(Request $request, ArticleRepository $articleRepository):Response
    // {
    //    $id = $request->query->getInt('id');

    // //    if($id===null){
    // //     throw new NotFoundHttpException("Article non trouvé");
    // //    }
    // // récupérer un article
    //     $article = $articleRepository->find($id);
    //     if($article===null){
    //         throw new NotFoundHttpException("Article non trouvé");
    //     }
    // //    
    //     return $this->render('article/item.html.twig', [
    //         'article'=>$article,
    //     ]);
    // }

    // catégoty

    #[Route('/articles/categories', name: 'categories')]
    public function categories(CategoryRepository $categories): Response
    {
        return $this->render('article/categories.html.twig', [
            'categories' => $categories->findAll(),
        ]);
    }

    #[Route('/articles/categories/{id}', name: 'category')]
    public function showArticles(CategoryRepository $categoryRepository, int $id): Response
    {
       $category = $categoryRepository->find($id);
       if($category === null) {
        throw new NotFoundHttpException('catégirie non trouvée');
       }
       $articleCollection = $category->getArticles();

        return $this->render('article/collectionArticles.html.twig', [
            'articleCollection' => $articleCollection,
            'category' => $category
        ]);
    } 

    // solution 3: avec composer require sensio/framework-extra-bundle avant symfony 6 (extension de Doctrine)
    #[Route('/articles/{id}', name: 'item')]
    public function article(Article $article): Response
    {
        return $this->render('article/item.html.twig', [
            'article' => $article
        ]);
    }

    
    // insérer une nouvelle catégorie
    #[Route('/category-form', name: 'category_form')]
    public function newCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        //    $category=$form->getData();
           $entityManager->persist($category);
           $entityManager->flush();
           $this->addFlash(
            'success',
            'Votre catégorie a été créée avec succès !'
           );
           return $this->redirectToRoute('categories');
        }
        
        return $this->renderForm('form/categoryForm.html.twig', [
            'form'=>$form
        ]);
    }

    // modifier une catégorie

    // #[Route('/category-update/{id}', name:'category-update', methods: ['GET', 'POST'])]
    // public function updateCategory(Category $category, EntityManagerInterface $entityManager, Request $request): Response
    // {
    //    $form = $this->createForm(CategoryType::class, $category);
    //    $form->handleRequest($request);
    //    if ($form->isSubmitted() && $form->isValid()) {
    //     $entityManager->persist($category);
    //     $entityManager->flush();
    //     $this->addFlash(
    //         'success',
    //         'Votre catégorie a été modifiée avec succès !'
    //     );
    //     return $this->redirectToRoute('categories');
    //    }

    //    return $this->render('form/categoryUpdate.html.twig', [
    //     'form'=>$form->createView()
    //    ]);
    // }

    // ajouter un article
    #[Route('/article-form', name: 'article_form')]
    public function newArticle(Request $request, EntityManagerInterface $entityManager ):Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $article->setCreatedAt(new \DateTime());
           $entityManager->persist( $article);
           $entityManager->flush();
           $this->addFlash(
            'success',
            'Votre article a été créé avec succès !'
           );
           return $this->redirectToRoute('articles');
        } 
        
        return $this->renderForm('form/articleForm.html.twig', [
            'form'=>$form
        ]);

    }

    // modifier article
    #[Route('/articles/update/{id}', name: 'article_update', methods: ['GET', 'POST'])]
    public function edit(Article $article, EntityManagerInterface $manager, Request $request):Response
    {
       $form = $this->createForm(ArticleType::class, $article);
       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid()){
        $article = $form->getData();
        $manager->persist($article);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre article a été modifié avec succès !'
        );
           return $this->redirectToRoute('articles');  
       }

       return $this->render('form/articleUpdate.html.twig', [
        'form'=>$form->createView()
       ]);
    }

    // supprimer un article
    #[Route('/articles/delete/{id}', name: 'article_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Article $article):Response
    {
        $manager->remove($article);
        $manager->flush();
        $this->addFlash(
            'success',
            'Votre article a été supprimé avec succès !'
        );
         return $this->redirectToRoute('articles');
    }

    }

