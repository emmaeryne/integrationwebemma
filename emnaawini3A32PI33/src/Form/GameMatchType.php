<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\GameMatch;
use App\Entity\Terrain;
use App\Entity\Tournoi;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameMatchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_match', null, [
                'widget' => 'single_text',
            ])
            ->add('score_equipe1')
            ->add('score_equipe2')
            ->add('statut_match')
            ->add('tournoi', EntityType::class, [
                'class' => Tournoi::class,
                'choice_label' => 'idTournoi', // Use the correct property name
            ])
            ->add('equipe1', EntityType::class, [
                'class' => Equipe::class,
                'choice_label' => 'id_equipe', // Ensure this matches the property in the Equipe entity
            ])
            ->add('equipe2', EntityType::class, [
                'class' => Equipe::class,
                'choice_label' => 'id_equipe', // Ensure this matches the property in the Equipe entity
            ])
            ->add('terrain', EntityType::class, [
                'class' => Terrain::class,
                'choice_label' => 'idTerrain', // Use the correct getter method
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GameMatch::class,
        ]);
    }
}
