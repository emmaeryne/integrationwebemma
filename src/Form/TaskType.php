<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Ajoute ici tes champs classiques
            ->add('recaptcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'task_form', // nom arbitraire, tu peux le changer
            ]);
    }
}
