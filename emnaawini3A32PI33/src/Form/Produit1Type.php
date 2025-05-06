<?php

namespace App\Form;

use App\Entity\CategorieProduit;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class Produit1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom_Produit')
            ->add('Prix')
            ->add('Stock_Dispo')
            ->add('Date')
            ->add('Fournisseur', ChoiceType::class, [
                'choices' => $options['fournisseurs'], // 🟢 injecté dynamiquement
                'placeholder' => 'Sélectionner un fournisseur',
                'required' => true,
                'mapped' => true,
                'attr' => ['class' => 'form-control', 'id' => 'produit1_Fournisseur'],
            ])
            ->add('Categorie', EntityType::class, [
                'class' => CategorieProduit::class,
                'choice_label' => 'nomcategorie',
                'placeholder' => 'Sélectionner une catégorie',
                'attr' => ['class' => 'form-control'],
            ]);
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            'fournisseurs' => [], // 🔥 nouvelle option
        ]);
    }
    
}
