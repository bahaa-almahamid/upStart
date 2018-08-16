<?php

namespace App\Form;

use App\Entity\Chat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChatFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text',TextareaType::class, ['attr'=>['class'=>'textarea']]);
            
        if ($options['standalone']) {
            $builder->add(
                'submit',
                 SubmitType::class,
                ['attr'=>['class'=>'buttonchat']]);
        }       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Chat::class,
            'standalone' => false
        ]);
       
    }
}
