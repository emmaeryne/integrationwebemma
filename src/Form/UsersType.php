<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id')
            ->add('username')
            ->add('email')
            ->add('password_hash')
            ->add('is_active')
            ->add('role')
            ->add('service_name')
            ->add('service_type')
            ->add('official_id')
            ->add('documents')
            ->add('specialty')
            ->add('experience_years')
            ->add('certifications')
            ->add('security_question_id')
            ->add('security_answer')
            ->add('tel')
            ->add('image')
            ->add('localisation')
            ->add('nom')
            ->add('prenom')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
