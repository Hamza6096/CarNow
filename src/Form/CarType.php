<?php

namespace App\Form;

use App\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', TextType::class, ['label' => 'Marque'])
            ->add('model', TextType::class, ['label' => 'Modèle'])
            ->add('matriculation', TextType::class, ['label' => 'Immatriculation'])
            ->add('matriculationDate', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
                'label' => 'Date Premère Immatriculation'
            ])
            ->add('nbSeats', TextType::class, ['label' => 'Nombre de places'])
            ->add('nbDoors', TextType::class, ['label' => 'Nombre de porte'])
            ->add('dailyPrice', MoneyType::class,['label' => 'Prix Journalier'])
            ->add('description', TextType::class, ['label' => 'Description'])
            ->add('data')
            ->add('category')
            ->add('energy')
            ->add('equipment')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
