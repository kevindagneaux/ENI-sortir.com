<?php

namespace App\Form;

use App\Entity\Campus;
use App\Modele\Search;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class, [
               'label'=> 'Campus: ',
                'class' => Campus::class,
                'choice_label' => 'nom',
            ])
            ->add('search', TextType::class, [
                'label' => 'Le nom de la sortie Contient: ',
                'required' => false,
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Entre',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Entre',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('organisateur', CheckboxType::class, [
                'label'    => 'Sorties dont je suis l\'organisateur/trice.',
                'required' => false,
            ])
            ->add('inscrit', CheckboxType::class, [
                'label'    => 'Sorties auxquelles je suis inscrit/e.',
                'required' => false,
            ])
            ->add('nonInscrit', CheckboxType::class, [
                'label'    => 'Sorties auxquelles je ne suis pas inscrit/e.',
                'required' => false,
            ])
            ->add('fini', CheckboxType::class, [
                'label'    => 'Sorties passées.',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
        ]);
    }
}
