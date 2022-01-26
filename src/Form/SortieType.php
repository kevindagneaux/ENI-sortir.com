<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                "label" => "Nom"
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'html5' => true,
                'widget' => "single_text",
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                    'hour' => 'Hour', 'minute' => 'Minute',
                ],
                "attr" => ["class" => "form_sortie"
                ]
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                "attr" => ["class" => "form_sortie"
                ]
            ])
            ->add('nbInscriptionMax', IntegerType::class, [
                "attr" => ["class" => "form_sortie"
                ]
            ])
            ->add('duree', IntegerType::class, [
                "attr" => ["class" => "form_sortie"
                ]
            ])
            ->add('infosSortie', TextareaType::class, [
                "attr" => ["class" => "form_sortie"
                ]
            ])
            ->add('ville', EntityType::class, [
                'label' => 'Ville',
                'class' => Ville::class,
                'choice_label' => 'nom',
                'mapped'=>false,
                "attr" => ["class" => "form_sortie"
                ]
            ])
            ->add('lieuSortie', EntityType::class, [
                'label' => 'Lieu',
                'class' => Lieu::class,
                'choice_label' => 'nom',
                "attr" => ["class" => "form_sortie"
                ]
            ])
            ->add('etat', SubmitType::class, [
                'label' => 'Enregistrer',
                "attr" => ["class" => "form_sortie"
                ]
            ])
            ->add('etatPublier', SubmitType::class, [
                'label' => 'Publier la sortie',
                "attr" => ["class" => "form_sortie"
                ]
            ])
            ->add('annuler', SubmitType::class, [
                'label' => 'Annuler',
                "attr" => ["class" => "form_sortie"
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
