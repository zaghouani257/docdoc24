<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedecinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('numtel')
            ->add('adresse')
            ->add('cinrecto')
            ->add('cinverso')
            ->add('matricule')
            ->add('cnam')
            ->add('cnss')
            ->add('specialite',ChoiceType::class,
                array('choices'=>array(
                    'Cardiologue'=>'Cardiologue',
                    'chirurgien Esthétique'=>'chirurgien Esthétique',
                    'Dentiste'=>'Dentiste',
                    'Darmatologue'=>'Darmatologue',
                    'Généraliste'=>'Généraliste',
                    'Gynécologue'=>'Gynécologue',
                    'Neurologue'=>'Neurologue',
                    'Ophtalmologue'=>'Ophtalmologue',
                    'Psychiatre'=>'Psychiatre',
                    'Sexologue'=>'Sexologue',
                    'Psychothérapeute'=>'Psychothérapeute',
                    'Pédiatre'=>'Pédiatre'
                ) ))
            ->add('disponabilite')
            ->add('image',FileType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
