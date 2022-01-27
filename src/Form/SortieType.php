<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    private $em;

    /**
     *
     * @param EntityManagerInterface $em
     */

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                "label" => "Nom de la sortie :",
                "attr" => ["class" => "form_sortie"
                ]
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie :',
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
                'label' => "Date limite d'inscription :",
                'html5' => true,
                'widget' => 'single_text',
                "attr" => ["class" => "form_sortie"
                ]
            ])
            ->add('nbInscriptionMax', IntegerType::class, [
                'label' => 'Nombre de places :',
                "attr" => ["class" => "form_sortie"
                ]
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée :',
                "attr" => ["class" => "form_sortie"
                ]
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description et infos :',
                "attr" => ["class" => "form_sortie"
                ]
            ])
            /*
            ->add('ville', EntityType::class, [
                'label' => 'Ville :',
                'class' => Ville::class,
                'choice_label' => 'nom',
                'mapped'=>false,
                "attr" => ["class" => "form_sortie"
                ]
            ])
            ->add('lieuSortie', EntityType::class, [
                'label' => 'Lieu :',
                'class' => Lieu::class,
                'choice_label' => 'nom',
                "attr" => ["class" => "form_sortie"
                ]
            ])
            */
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
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

    protected function addElements(FormInterface $form, Lieu $lieu = null)
    {
        $ville = null;

        $form->add('ville', EntityType::class, [
            'required' => true,
            'placeholder' => 'Selectionner une ville',
            'class' => 'App\Entity\Ville',
            'choice_label' => "nom",
            'data' => $ville,
            'mapped' => false,
            "attr" => ["class" => "form_sortie"
            ]
        ]);

        $lieus = array();

        if ($ville) {

            $repoLieus = $this->em->getRepository('App:Lieu');

            $lieus = $repoLieus->createQueryBuilder("q")
                ->where("q.lieuVille = :id")
                ->setParameter('id', $ville->getId())
                ->getQuery()
                ->getResult();
        }

        $form->add('lieuSortie', EntityType::class, [
            'required' => true,
            'placeholder' => "Selectionner une ville d'abord",
            'class' => 'App\Entity\Lieu',
            'choices' => $lieus,
            "attr" => ["class" => "form_sortie"
            ]
        ]);
    }

    function onPreSubmit(FormEvent $events)
    {
        $form = $events->getForm();
        $data = $events->getData();

        $lieu = $this->em->getRepository('App:Lieu')->find($data['nom']);

        $this->addElements($form, $lieu);
    }

    function onPreSetData(FormEvent $event)
    {
        $sortie = $event->getData();
        $form = $event->getForm();

        $lieu = null;

        $this->addElements($form, $lieu);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }


}
