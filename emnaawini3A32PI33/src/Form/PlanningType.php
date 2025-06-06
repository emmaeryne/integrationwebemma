<?php
namespace App\Form;

use App\Entity\Planning;
use App\Entity\Cours;
use App\Entity\Users; // Modifiez cette ligne
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeActivite')
            ->add('datePlanning')
            ->add('status')
            ->add('cours', EntityType::class, [
                'class' => Cours::class,
                'choice_label' => 'Nom_Cours',
                'placeholder' => 'Choisir un cours',
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Planning::class,
        ]);
    }
}