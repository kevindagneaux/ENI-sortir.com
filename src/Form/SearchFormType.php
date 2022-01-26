<?php

namespace App\Form;

use App\Entity\Campus;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
                'label' => 'Le nom de la sortie Contient: '
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Entre',
                'widget' => 'single_text',
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Entre',
                'widget' => 'single_text',
            ])
            ->add('organisateur', CheckboxType::class, [
                'label'    => 'Sorties dont je suis l\'organisateur/trice.',
            ])
            ->add('inscrit', CheckboxType::class, [
                'label'    => 'Sorties auxquelles je suis inscrit/e.',
            ])
            ->add('nonInscrit', CheckboxType::class, [
                'label'    => 'Sorties auxquelles je ne suis pas inscrit/e.',
            ])
            ->add('fini', CheckboxType::class, [
                'label'    => 'Sorties passées.',
            ])
        ;
    }
}
