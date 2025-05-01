<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;
use VictorPrdh\RecaptchaBundle\Validator\Constraints\Recaptcha;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('password_hash', PasswordType::class)
            ->add('tel')
            ->add('image', FileType::class, [
                'label' => 'Image de profil',
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '8M',
                    ])
                ],
                'required' => false,
            ])
            ->add('localisation')
            ->add('nom')
            ->add('prenom')
            ->add('recaptcha', ReCaptchaType::class, [
                'mapped' => false,
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
