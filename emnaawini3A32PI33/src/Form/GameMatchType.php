<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\GameMatch;
use App\Entity\Terrain;
use App\Entity\Tournoi;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('statut_match', ChoiceType::class, [
                'choices' => [
                    'Ongoing' => 'ongoing',
                    'Completed' => 'completed',
                    'Cancelled' => 'cancelled',
                ],
                'placeholder' => 'Select a status',
                'required' => true,
            ])
            ->add('tournoi', EntityType::class, [
                'class' => Tournoi::class,
                'choice_label' => 'nomTournoi', // Display the tournament name
                'placeholder' => 'Select a tournament',
                'required' => false,
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

    private function calculateProbabilities(?StatistiquesEquipe $statsEquipe1, ?StatistiquesEquipe $statsEquipe2): array
    {
        if (!$statsEquipe1 || !$statsEquipe2) {
            return ['equipe1' => 'N/A', 'equipe2' => 'N/A'];
        }

        // Debugging: Check the statistics values
        dump([
            'statsEquipe1' => [
                'matchsJoues' => $statsEquipe1->getMatchsJoues(),
                'butsMarques' => $statsEquipe1->getButsMarques(),
                'butsEncaisses' => $statsEquipe1->getButsEncaisses(),
            ],
            'statsEquipe2' => [
                'matchsJoues' => $statsEquipe2->getMatchsJoues(),
                'butsMarques' => $statsEquipe2->getButsMarques(),
                'butsEncaisses' => $statsEquipe2->getButsEncaisses(),
            ],
        ]);

        // Calculate offensive and defensive strength for each team
        $offensiveStrengthEquipe1 = $statsEquipe1->getButsMarques() / max($statsEquipe1->getMatchsJoues(), 1);
        $defensiveStrengthEquipe1 = $statsEquipe1->getButsEncaisses() / max($statsEquipe1->getMatchsJoues(), 1);

        $offensiveStrengthEquipe2 = $statsEquipe2->getButsMarques() / max($statsEquipe2->getMatchsJoues(), 1);
        $defensiveStrengthEquipe2 = $statsEquipe2->getButsEncaisses() / max($statsEquipe2->getMatchsJoues(), 1);

        // Debugging: Check calculated strengths
        dump([
            'offensiveStrengthEquipe1' => $offensiveStrengthEquipe1,
            'defensiveStrengthEquipe1' => $defensiveStrengthEquipe1,
            'offensiveStrengthEquipe2' => $offensiveStrengthEquipe2,
            'defensiveStrengthEquipe2' => $defensiveStrengthEquipe2,
        ]);

        // Calculate team strength (offensive vs defensive)
        $strengthEquipe1 = $offensiveStrengthEquipe1 / max($defensiveStrengthEquipe2, 1);
        $strengthEquipe2 = $offensiveStrengthEquipe2 / max($defensiveStrengthEquipe1, 1);

        // Normalize probabilities
        $totalStrength = $strengthEquipe1 + $strengthEquipe2;
        if ($totalStrength > 0) {
            $probEquipe1 = ($strengthEquipe1 / $totalStrength) * 100;
            $probEquipe2 = ($strengthEquipe2 / $totalStrength) * 100;
        } else {
            $probEquipe1 = $probEquipe2 = 50; // Equal probability if no data
        }

        return [
            'equipe1' => round($probEquipe1, 2),
            'equipe2' => round($probEquipe2, 2),
        ];
    }
}
