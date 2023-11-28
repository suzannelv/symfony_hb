<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\UserSecurity;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ArticleType extends AbstractType
{
    // public function __construct(private CategoryRepository $categoryRepository){}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
          
                'attr' => [
                    'class'=>'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 my-5',
                ],
                'label'=>'Titre de l\'article',
                'label_attr'=> [
                    'class'=>'block my-6 text-sm font-medium text-gray-900 dark:text-white',
                ],
                'constraints'=>[new Length(['max'=>20])]    
                ])
            ->add('content', TextareaType::class,[
                    'attr'=>[
                        'class'=>'block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    ],
                    'label'=>'Contenu de l\'article',
                    'label_attr'=> [
                        'class'=>'block my-6 text-sm font-medium text-gray-900 dark:text-white',
                    ]    
                 ] )
            ->add('category', EntityType::class,[
                    'class'=>Category::class,
                    // 'choices'=>$this->categoryRepository->findAll(),
                    'choice_label'=>'name',
                    'label'=>'Catégorie',
                    'label_attr'=>[
                        'class'=>'block my-6 text-sm font-medium text-gray-900 dark:text-white'
                    ]
        
            ] )
            ->add('author', EntityType::class,[
                'class'=>UserSecurity::class,
                'choice_label'=>'email',
                'label'=>'Auteur',
                'label_attr'=>[
                    'class'=>'block my-6 text-sm font-medium text-gray-900 dark:text-white'
                ]
            ])
        
            ->add('visible', CheckboxType::class, [
                'attr'=>[
                    'class'=>'w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600'
                ],
                'label'=>'Visibilité',
                'label_attr'=>[
                    'class'=>'ms-2 text-sm font-medium text-gray-900 dark:text-gray-300'
                ],
                'constraints'=>[new NotBlank()]
            ])
            ->add('save', SubmitType::class, [
                'attr'=>[
                    'class'=>'inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800',
                ],
                'label'=>'Créer mon article'
            ])
            // ->add('category_id',EntityType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
