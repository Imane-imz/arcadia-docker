<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Nourriture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class NourritureFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('animal', EntityType::class, [
            'class' => Animal::class,
            'choice_label' => 'prenom', // Affiche le nom de la catégorie dans le select
            'placeholder' => 'Séléctionner un animal',
        ])

            ->add('nourriture', TextType::class, [
                'label' => 'Nourriture'
            ])

            ->add('quantite', IntegerType::class, [
                'label' => 'Quantité'
            ])

            ->add('date', DateType::class, [
                'label' => 'Date'
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
            'data_class' => Nourriture::class,
        ]);
    }
}
