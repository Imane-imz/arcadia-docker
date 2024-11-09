<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Habitat;
use App\Entity\Race;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class AnimalFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
 
            ->add('prenom', TextType::class, [
                'label' => 'Prénom de l\'animal'
            ])

            ->add('race', EntityType::class, [
                'class' => Race::class,
                'choice_label' => 'label', // Affiche le nom de la catégorie dans le select
                'placeholder' => 'Séléctionner une race',
            ])
           /* Comment mettre la liste des races ? */
          
           ->add('habitat', EntityType::class, [
                'class' => Habitat::class,
                'choice_label' => 'nom',
                'placeholder' => 'Sélectionner un habitat'
            ])

            ->add('habitatReel', TextType::class, [
                'label' => 'Nom spécifique de l\ihabitat'
            ])

            ->add('etat', TextType::class, [
                'label' => 'État général de l\'animal'
            ])

            ->add('nourritureGlobale', TextType::class, [
                'label' => 'Nourriture de l\'animal'
            ])

            ->add('image', FileType::class, [
                'label' => 'Image (JPG, PNG file)',
                'required' => false,
                'mapped' => false,
            ])

            ->add('confirmation', CheckboxType::class, [
                'mapped' => false,
                'label' => "Je confirme l'exactitude des informations entrées.",
                'constraints' => [
                    new IsTrue(message : "Veuillez cocher la case pour ajouter le service."),
                ]
            ])
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
