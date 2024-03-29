<?php

namespace App\Form;

use App\Entity\Technic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TechnicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add(
                'name', 
                TextType::class,
                [
                    'label' => '✒️ Quel est le nom de la solution ?',
                    'attr' => [
                        
                        'placeholder'    => 'Exemple : carburants synthétiques',
                    ],

                    'required'   => true,
                ]
            )

            ->add(
                'textDescription',
                TextareaType::class,
                [
                    'label' => '✍️ Décrire la solution en quelques mots ou paragraphes',
                    'required'     => false,
                    'sanitize_html' => true,
                    'attr' => [
                        'class' => "tinymce",
                    ],
                ]
            )

            ->add(
                'pros',
                TextareaType::class,
                [
                    'label' => '🙂 intérêts / avantages de la solution ?',
                    'required'     => false,
                    'sanitize_html' => true,
                    'attr' => [
                        'class' => "tinymceProsCons",
                    ],
                ]
            )

            ->add(
                'cons',
                TextareaType::class,
                [
                    'label' => '🙁 limites / inconvénients  de la solution ?',
                    'required'     => false,
                    'sanitize_html' => true,
                    'attr' => [
                        'class' => "tinymceProsCons",
                    ],
                ]
            )

            ->add(
                'notes',
                TextareaType::class,
                [
                    'label' => '🧐 Anectodes / infos intéressantes ?',
                    'required'     => false,
                    'sanitize_html' => true,
                    'attr' => [
                        'class' => "tinymceProsCons",
                    ],
                ]
            )

            ->add(
                'conclusion',
                TextareaType::class,
                [
                    'label' => '😙 Conclusion / utile à retenir',
                    'required'     => false,
                    'sanitize_html' => true,
                    'attr' => [
                        'class' => "tinymceProsCons",
                    ],
                ]
            )

            ->add(
                'sources',
                TextareaType::class,
                [
                    'label' => '🤔 Sources',
                    'required'     => false,
                    'sanitize_html' => true,
                    'attr' => [
                        'class' => "tinymceProsCons",
                    ],
                ]
            )

            ->add(
                'licence',
                TextareaType::class,
                [
                    'label' => '©️ Licences icônes utilisées',
                    'required'     => false,
                    'sanitize_html' => true,

                ]
            )

            ->add(
                'progressBar',
                IntegerType::class,
                [
                    'label' => '🌳 Pourcentage de réduction des émissions estimé (entre 1 et 100)',
                    'required'     => false,
                    'attr'     => array(
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ),
                ]
            )

            
            ->add(
                'status',
                TextareaType::class,
                [
                    'label' => '🏗️ La solution est-elle déjà utilisée ?',
                    'required'     => false,
                    'sanitize_html' => true,
                    'attr' => [
                        'class' => "tinymceProsCons",
                    ],

                ]
            )


        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Technic::class,
        ]);
    }
}
