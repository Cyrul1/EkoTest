<?php

namespace App\Form;

use App\Entity\Artykul;
use App\Entity\Magazyny;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArtykulType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nazwa')
            ->add('JednostkaMiary')
            ->add('Magazyny', EntityType::class, [
                'class' => Magazyny::class,
                'choice_label' => 'Nazwa',
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('zapisz', SubmitType::class, [
                'label' => 'Zapisz',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artykul::class,
        ]);
    }
}
