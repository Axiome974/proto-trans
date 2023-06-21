<?php

namespace App\Form;

use App\Entity\Quotation;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuotationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                "label" => "Prénom"
            ])
            ->add('lastname', TextType::class, [
                "label" => "Nom"
            ])
            ->add('email', EmailType::class, [
                "label" => "Email"
            ])
            ->add('phone', TextType::class, [
                "label" => "Téléphone"
            ])
            ->add('fax', TextType::class, [
                "label" => "Fax",
                "required"  => false
            ])
            ->add('departureCity', TextType::class, [
                "label" => "Ville de départ"
            ])
            ->add('departureAt', DateTimeType::class, [
                "label" => "Date et heure de départ",
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
            ])
            ->add('arrivalCity', TextType::class, [
                "label" => "Ville d'arrivée"
            ])
            ->add('arrivalAt', DateTimeType::class, [
                "label" => "Date et heure de retour",
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
            ])
            ->add('passengers', NumberType::class, [
                "label"     => "Nombre de passagers"
            ])
            ->add('isUsingBus', CheckboxType::class, [
                "label" => "Utilisation du bus sur place",
                "required"  => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quotation::class,
        ]);
    }
}
