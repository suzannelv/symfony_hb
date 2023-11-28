<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\UserSecurity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private const NB_CATEGORIES = 15;
    private const NB_ARTICLES = 150;
    private const NB_USER = 10;
    public function __construct(private UserPasswordHasherInterface $passwordHasher){}


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");

        $categories = [];

        for ($i = 0; $i < self::NB_CATEGORIES; $i++) {
            $category = new Category();
            $category->setName($faker->realTextBetween(3, 10));

            $manager->persist($category);
            $categories[] = $category;
        }

        // role: user_security / admin
        $authorUsers=[];
        for($i = 0; $i<25; $i++){
            $authorUser=new UserSecurity();
            $plainPassword = "test";
            $hashedPassword = $this->passwordHasher->hashPassword(
                $authorUser,
                $plainPassword
            );

            $authorUser->setEmail($faker->email())
                ->setRoles(mt_rand(0, 1) ===1 ? ['ROLE_USER']: ['ROLE_ADMIN'])
                ->setPassword($hashedPassword);

            $manager->persist($authorUser);
            $authorUsers[] = $authorUser;

        }
        for ($i = 0; $i < self::NB_ARTICLES; $i++) {
            $article = new Article();
            $article
                ->setTitle($faker->realTextBetween(3, 10))
                ->setContent($faker->realTextBetween(500, 1400))
                ->setCreatedAt($faker->dateTimeBetween('-2 years'))
                ->setVisible($faker->boolean(80))
                ->setCategory($faker->randomElement($categories))
                ->setAuthor($faker->randomElement($authorUsers));

            $manager->persist($article);
        }

        // user
        for ($i = 0; $i < self::NB_USER; $i++) {
            $user = new User();
            $user->setEmail($faker->email())
                 ->setSubscribed($faker->boolean()) 
                 ->setSubscriptionDate($faker->dateTimeBetween('-3 years'));
            $manager->persist($user);
        }
        $manager->flush();
    }
}


