<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\TypeAbonnement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['is_edit'] ?? false;

        $builder
            ->add('typeAbonnement', EntityType::class, [
                'class' => TypeAbonnement::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un type d\'abonnement',
                'attr' => ['class' => 'form-control']
            ])
            ->add('dateDebut', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('dateFin', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ]);

        if ($isEdit) {
            // Mode édition - Afficher la liste déroulante
            $builder->add('statut', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'en attente',
                    'En cours' => 'en cours',
                    'Terminé' => 'terminé',
                    'Annulé' => 'annulé',
                ],
                'attr' => ['class' => 'form-control']
            ]);
        } else {
            // Mode création - Champ caché avec valeur par défaut
            $builder->add('statut', HiddenType::class, [
                'data' => 'en attente',
                'attr' => ['class' => 'd-none']
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'is_edit' => false
        ]);
    }
}