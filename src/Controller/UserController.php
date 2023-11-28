<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SubscribersType;
use App\Repository\UserRepository;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    #[Route('/user', name: 'newsletter')]
    public function newsletter(Request $request, EntityManagerInterface $em, SendEmailService $emailService): Response
    {

        $user = new User();
        $form = $this->createForm(SubscribersType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           $user->setSubscribed(true);
           $user->setSubscriptionDate(new \DateTime());
           $em->persist($user);
           $em->flush();
           $this->addFlash(
            'success',
            'Vous avez abonnée !' . $user->getEmail()
           );

        // utiliser la service pour envoyer email
           $emailService->sendEmail($user);

           return $this->redirectToRoute('newsletter_confirm');

        }
        return $this->renderForm('user/index.html.twig', [
            'newsletter_form' => $form,
        ]);
    }

    #[Route('/user/confirm', name: 'newsletter_confirm')]
    public function confirm(): Response
    {   

        return $this->renderForm('user/subscrib_confirm.html.twig', [
            'confirm_message' => "Félicititation"
        ]);
    }

    // pour désabonndé
    #[Route('/user/unsubscribed/{id}', name: 'newsletter_unsubscribed')]
public function unsubscribed(User $user, EntityManagerInterface $em): Response
{
    if ($user->isSubscribed() === true) {
        $user->setSubscribed(false);
    }

    $em->flush();

    $this->addFlash(
        'success',
        'Vous avez désabonné!'
    );

    return $this->render('mailer/unsubscribed_confirm.html.twig', [
        'user' => $user
    ]);
}


    #[Route('/user/unsubscribed/confirm', name: 'newsletter_unsubscribed_confirm')]
    public function unsubcribeConfirm(User $user): Response
    {
       
        return $this->render('mailer/unsubscribed_confirm.html.twig');
    }

   

}
