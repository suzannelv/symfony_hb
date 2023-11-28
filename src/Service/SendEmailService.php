<?php

namespace App\Service;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
// use Symfony\Component\Mime\Email;

class SendEmailService
{

  public function __construct(
    private MailerInterface $mailer,
    private string $adminEmail)
    {

    }
  public function sendEmail(User $user):void // "lucas@gmail.com"
  {
      $email = (new TemplatedEmail())
            ->from($this->adminEmail)
            ->to($user->getEmail()) 
            ->subject('Confirmation d\'inscription')
            ->htmlTemplate('mailer/signUp.html.twig')
            ->context([
              'expiration_date' => new \DateTime('+7 days'),
              'user'=> $user
            ]);
     $this->mailer->send($email); 

  }
}
