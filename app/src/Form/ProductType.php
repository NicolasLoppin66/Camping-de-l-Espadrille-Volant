<?php

namespace App\Form;

use App\Entity\Owners;
use App\Entity\Products;
use App\Entity\RentalsTypes;
use App\Repository\OwnersRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rental_type', EntityType::class, [
                'label' => 'Type de produit',
                'class' => RentalsTypes::class,
                'placeholder' => 'Type de produit',
                'choice_label' => 'label',
                'expanded' => false,
                'multiple' => false,
                'mapped' => true
            ])
            ->add('label', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Titre'
                ],
                'required' => true
            ])
            ->add('description', TextareaType::class, [

            ])
            ->add('owner_id', EntityType::class, [
                'label' => 'Propriétaire',
                'class' => Owners::class,
                'placeholder' => 'Propriétaire',
                'choice_label' => 'lastname',
                'expanded' => false,
                'multiple' => false,
                'mapped' => true,
                'query_builder' => function (OwnersRepository $ownersRepo) {
                    return $ownersRepo->findAllOrderedByLabel();
                },
            ])
            ->add('Enregistrer', SubmitType::class, [

            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}