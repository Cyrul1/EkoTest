<?php

namespace App\Form;

use App\Entity\Artykul;
use App\Entity\Magazyny;
use App\Repository\ArtykulRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArtykulUserType extends AbstractType
{
    private $artykulRepository;

    public function __construct(ArtykulRepository $artykulRepository)
    {
        $this->artykulRepository = $artykulRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $nazwyArtykulow = [];
        $artykuly = $this->artykulRepository->findAll();
        foreach ($artykuly as $artykul) {
            $nazwyArtykulow[$artykul->getNazwa()] = $artykul->getNazwa();
        }

        $builder
            ->add('Nazwa', EntityType::class, [
                'class' => Artykul::class,
                'choices' => $nazwyArtykulow,
                'choice_label' => 'nazwa',
                'required' => true,
            ])
            ->add('JednostkaMiary')
            ->add('IloscPrzyjeta')
            ->add('VAT')
            ->add('Cena')
            ->add('IloscWydana')
            ->add('magazyny', EntityType::class, [
                'class' => Magazyny::class,
                'choices' => $options['magazyny'],
                'choice_label' => 'nazwa',
                'required' => true,
            ])
            ->add('zapisz', SubmitType::class, [
                'label' => 'Zapisz',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artykul::class,
            'magazyny' => [],
        ]);
    }
}