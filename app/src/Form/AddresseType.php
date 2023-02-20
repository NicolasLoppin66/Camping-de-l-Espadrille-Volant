<?php

namespace App\Form;

use App\Entity\Addresses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class AddresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('num', NumberType::class, [
                'label' => "numéro de rue",
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('road_type', TextType::class, [
                'label' => "Type de voie",
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('road_name', TextType::class, [
                'label' => "Nom de la voie",
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('zip', NumberType::class, [
                'label' => "Code Postal",
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('city', TextType::class, [
                'label' => "Ville",
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Addresses::class,
        ]);
    }
}