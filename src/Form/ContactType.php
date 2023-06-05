<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                "label" => "Email",
                "attr"=> [
                    "placeholder"   => "contact@email.com"
                    ]
            ])
            ->add('name', TextType::class, [
                "label" => "Nom et prénom",
                "attr"=> [
                    "placeholder"   => "Nom et prénom"
                    ]
            ])
            ->add('reason', ChoiceType::class, [
                "label"     => "Sujet",
                "choices"   => [
                    "Devis" => "Devis",
                    "Réclamation" => "Réclamation",
                    "Autre demande" => "Autre demande"
                ]
            ])
            ->add('message', TextareaType::class, [
                "label"     => "Votre message",

                "attr"      => [
                    "rows"  => 10,
                    "placeholder" => "Nous sommes à votre écoute..."

                ]
            ])
            ->add('submit',  SubmitType::class, [
                'label'         => 'Envoyer',
                "attr"          => [
                    "class" => "btn btn-success btn-outline"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
