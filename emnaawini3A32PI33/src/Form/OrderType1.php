<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class OrderType1 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('carrierName', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Transporteur'
            ])
            ->add('carrierPrice', MoneyType::class, [
                'currency' => 'TND',
                'divisor' => 100,
                'attr' => ['class' => 'form-control'],
                'label' => 'Frais de port'
            ])
            ->add('isPaid', CheckboxType::class, [
                'label' => 'Payée',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}