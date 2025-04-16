<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du service',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom du service est obligatoire.']),
                    new Assert\Length([
                        'min' => 3,
                        'max' => 100,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ0-9\s]+$/',
                        'message' => 'Le nom du service ne doit contenir que des lettres, des chiffres et des espaces.',
                    ]),
                ],
                'attr' => ['placeholder' => 'Entrez le nom du service'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => 1000,
                        'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
                'attr' => ['placeholder' => 'Description (optionnelle)', 'rows' => 4],
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix (€)',
                'scale' => 2,
                'html5' => true,
                'attr' => [
                    'step' => '0.01',
                    'min' => 0,
                    'placeholder' => 'Ex: 12,50',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le prix est obligatoire.']),
                    new Assert\Positive(['message' => 'Le prix doit être positif.']),
                ],
                'invalid_message' => 'Veuillez entrer un prix valide (nombre positif).',
            ])
            ->add('estActif', ChoiceType::class, [
                'label' => 'Actif ?',
                'choices' => ['Oui' => true, 'Non' => false],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez indiquer si le service est actif.']),
                ],
            ])
            ->add('capaciteMax', IntegerType::class, [
                'label' => 'Capacité maximale',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La capacité maximale est obligatoire.']),
                    new Assert\Positive(['message' => 'La capacité doit être positive.']),
                    new Assert\Range([
                        'min' => 1,
                        'max' => 100,
                        'notInRangeMessage' => 'La capacité doit être comprise entre {{ min }} et {{ max }} personnes.',
                    ]),
                ],
                'attr' => ['placeholder' => 'Ex: 50'],
            ])
            ->add('categorie', TextType::class, [
                'label' => 'Catégorie',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La catégorie est obligatoire.']),
                    new Assert\Length([
                        'max' => 50,
                        'maxMessage' => 'La catégorie ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
                'attr' => ['placeholder' => 'Ex: Sport'],
            ])
            ->add('dureeMinutes', IntegerType::class, [
                'label' => 'Durée (minutes)',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La durée est obligatoire.']),
                    new Assert\Positive(['message' => 'La durée doit être positive.']),
                    new Assert\Range([
                        'min' => 15,
                        'max' => 240,
                        'notInRangeMessage' => 'La durée doit être comprise entre {{ min }} et {{ max }} minutes.',
                    ]),
                ],
                'attr' => ['placeholder' => 'Ex: 60'],
            ])
            ->add('niveau', ChoiceType::class, [
                'label' => 'Niveau de difficulté',
                'choices' => [
                    'Débutant' => 1,
                    'Intermédiaire' => 2,
                    'Avancé' => 3,
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le niveau de difficulté est obligatoire.']),
                    new Assert\Range([
                        'min' => 1,
                        'max' => 3,
                        'notInRangeMessage' => 'Le niveau doit être compris entre {{ min }} et {{ max }}.',
                    ]),
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (JPG, PNG)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image au format JPG ou PNG.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}