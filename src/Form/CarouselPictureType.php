<?php

namespace App\Form;

use App\Entity\CarouselPicture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarouselPictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class,[
                "mapped"    =>  false,
                "label"     => "Importer une image"
            ])
            ->add('submit', SubmitType::class, [
                "label"     => "Ajouter l'image",
                "attr"      => [
                    "class" => "btn btn-success"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CarouselPicture::class,
        ]);
    }
}
