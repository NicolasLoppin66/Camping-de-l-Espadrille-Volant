<?php

namespace App\Form;

use App\Entity\Bookings;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Products;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('check_in', DateType::class, [
                'label' => 'Date d\'arrivée',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'js-datepicker',
                    'data-provide' => 'datepicker',
                ],

            ])
            ->add('check_out', DateType::class, [
                'label' => 'Date de départ',
                //                'widget' => 'choice',
//                'input'  => 'datetime_immutable',
//                'attr' => ['class' => 'js-datepicker'],
//                'format' => 'yyyy-MM-dd',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'js-datepicker',
                    'data-provide' => 'datepicker',
                ],
            ])
            ->add('nb_adults', NumberType::class, [
                'label' => 'Nombre d\'adultes',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '1'
                ],
                'required' => true
            ])
            ->add('nb_kids', NumberType::class, [
                'label' => 'Nombre d\'enfants',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '0'
                ],
                'required' => true
            ])
            ->add('pool_access_adults', NumberType::class, [
                'label' => 'Nombre de jours de piscines adultes (nb d\'adultes x nb de jours)',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'piscine adultes'
                ],
                'required' => true
            ])
            ->add('pool_access_kids', NumberType::class, [
                'label' => 'Nombre de jours de piscines enfants (nb d\'enfants x nb de jours)',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'piscine enfants'
                ],
                'required' => true
            ])

            ->add('product_id', EntityType::class, [
                'class' => Products::class,
                'attr' => [
                    'class' => 'form-control',
                ],
                'placeholder' => 'Quel hébergement / emplacement',
                'choice_label' => 'label',
                'expanded' => false,
                'multiple' => false,
                'mapped' => true
            ])

            ->add('Enregistrer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bookings::class,
        ]);
    }
}