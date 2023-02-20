<?php

namespace App\Form;

use App\Entity\Clients;
use DateTime;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormTypeInterface;


class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $date = new DateTime();
        $builder
            ->add('firstname', TextType::class, [
                'label' => "Prénom",
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('lastname', TextType::class, [
                'label' => "Nom",
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('telephone', TextType::class, [
                'label' => "Telephone",
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('email', TextType::class, [
                'label' => "Email",
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
                /*            ->add('eraseDataDay', DateTimeType::class, [
                ])*/
            ->add('dataRetentionConsent', CheckboxType::class, [
                'label' => "Conservation de vos données pendant un an",
                'mapped' => true,
            ])
            //->add('address_id')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Clients::class,
        ]);
    }
}