<?php

namespace App\Form;

use App\Entity\CategorieMedicale;
use App\Entity\Question;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class,[
                'attr' =>['class'=>'form-control',
                    'placeholder'=>"Ajouter le titre de la question...",
                    'rows'=>"4",

                ],



            ])
            ->add('symptomes',TextareaType::class,[
                'attr' =>['class'=>'form-control',
                    'placeholder'=>"Fournir plus de details ici...",
                    'rows'=>"4",

                ],



            ])
            ->add('taille')
            ->add('poids')
            ->add('isTreated',CheckboxType::class,['label' => 'Traitement en cours'])
            ->add('isAntMed',CheckboxType::class,['label' => 'Antécédents Médicaux'])
            ->add('isNameShown', CheckboxType::class,['label' => 'afficher votre nom'])
            ->add('categorieMedicale', EntityType::class, [
                'class'=>CategorieMedicale::class,
                'choice_label'=>'nom'])
            ->add('user',EntityType::class, [
                'class'=>User::class,
                'choice_label'=>'id'

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
