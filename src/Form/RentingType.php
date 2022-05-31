<?php

namespace App\Form;

use App\Entity\Renting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RentingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ])
            ->add('end', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ])
            ->add('rentValidate', HiddenType::class)
            ->add('paymentDate', HiddenType::class)
            ->add('amout',MoneyType::class)
            ->add('typeOfPayment', HiddenType::class)
            ->add('plannedDuration', HiddenType::class)
            ->add('actualDuration', HiddenType::class)
            ->add('dailyPrice')
            ->add('car', HiddenType::class)
            ->add('user', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Renting::class,
        ]);
    }
}
