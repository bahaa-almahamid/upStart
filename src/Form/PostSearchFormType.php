<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Post;
use App\DTO\PostSearch;
use App\Form\PostSearchFormType;

class PostSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder->add('post', EntityType::class, ['class'=>Post::class, 'choice_label'=> 'title'])
       ->add('search', TextType::class, ['required'=>false]);

       if ($options['standalone']) {

            $builder->add(
                'submit', SubmitType::class, ['label' => 'search'],
                ['attr' => ['class' => 'btn btn-info']]
            );
       }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => PostSearch::class,
                'standalone' => false
            ]
        );
    }
}
