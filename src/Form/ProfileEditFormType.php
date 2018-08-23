<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ProfileEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('password',PasswordType::class)
            ->add('email', EmailType::class)
            ->add('address', TextType::class)
            ->add('about', TextType::class)
            ->add('picture', FileType::class,
            ['required'=>false],
            ['attr' => ['class' => 'form-control']]);
            if ($options['standalone']) {
                $builder->add(
                    'submit', 
                    SubmitType::class, 
                    ['attr' => ['class' => 'btn btn-info']]
                );
            }
        }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'standalone' => false
        ]);
    }
}
