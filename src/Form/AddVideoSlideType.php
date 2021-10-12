<?php

namespace App\Form;

use App\Entity\Slide;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AddVideoSlideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'address',
                TextType::class,
                [
                    'label' => 'Code de la vidéo',
                    'attr' => [

                        'placeholder'    => 'Écrire ici le code de la vidéo',
                    ],
                ]
            )
            ->add(
                'caption',
                TextType::class,
                [
                    'label' => "Légende / Titre (facultatifs) pour cette vidéo",

                    'attr' => [

                        'placeholder'    => "Écrire ici",
                    ],

                    'required'   => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Slide::class,
        ]);
    }
}
