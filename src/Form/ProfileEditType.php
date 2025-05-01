<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;




class ProfileEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'First Name'
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Last Name'
            ])
            ->add('email', EmailType::class)
            ->add('tel', TextType::class, [
                'label' => 'Phone Number'
            ])
            ->add('localisation', TextType::class, [
                'label' => 'Location'
            ])
            ->add('image', FileType::class, [
                'label' => 'Profile Picture',
                'required' => false,
                'mapped' => false
            ])
            ->add('currentPassword', PasswordType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Mot de passe actuel',
            ])
            ->add('newPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'required' => false,
                'first_options'  => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Confirmer le nouveau mot de passe'],
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
            ])
            // Add coach-specific fields
        ->add('experienceYears', IntegerType::class, [
            'label' => 'Années d\'expérience',
            'required' => false,
            'attr' => [
                'min' => 0,
                'max' => 50
            ]
        ])
        ->add('specialty', ChoiceType::class, [
            'label' => 'Spécialité',
            'required' => false,
            'choices' => [
                'Personal Training' => 'personal_training',
                'Yoga' => 'yoga',
                'CrossFit' => 'crossfit',
                'Nutrition' => 'nutrition',
                'Bodybuilding' => 'bodybuilding',
                'Cardio' => 'cardio',
                'Pilates' => 'pilates',
                'Martial Arts' => 'martial_arts'
            ]
        ]);
    }
        
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}