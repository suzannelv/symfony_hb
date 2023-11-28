<?php

namespace App\Security\Voter;

use App\Entity\Article;
use App\Entity\UserSecurity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleVoter extends Voter
{
    public const EDIT = 'ARTICLE_EDIT';
    public const DELETE = 'ARTICLE_DELETE';
    public function __construct(
        private Security $security
    ){

    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof Article;
    } 

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if($this->security->isGranted('ROLE_ADMIN')){
            return true;
        }

        $user = $token->getUser();
    
        if (!$user instanceof UserSecurity || !$subject instanceof Article) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT: 
            case self::DELETE:
                return $subject->getAuthor() === $user;
        }

        return false;
    }
}
