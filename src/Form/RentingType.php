<?php

namespace App\Form;

use App\Entity\Renting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RentingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start')
            ->add('end')
            ->add('rentValidate')
            ->add('paymentDate')
            ->add('amout')
            ->add('typeOfPayment')
            ->add('plannedDuration')
            ->add('actualDuration')
            ->add('dailyPrice')
            ->add('car')
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Renting::class,
        ]);
    }
}
