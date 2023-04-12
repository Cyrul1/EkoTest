<?php

namespace App\Controller;

use App\Entity\Artykul;
use App\Entity\Magazyny;
use App\Form\ArtykulType;
use App\Form\ArtykulUserType;
use App\Repository\ArtykulRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function myArticles(Request $request): Response
    {
        $user = $this->getUser();
        $magazyny = $user->getUserMagazyn()->toArray();

        $artykuly = [];
        $form = $this->createFormBuilder()
            ->add('Magazyny', EntityType::class, [
                'class' => Magazyny::class,
                'choices' => $magazyny,
                'choice_label' => 'nazwa',
                'placeholder' => '-- wybierz magazyn --',
                'required' => true,
            ])
            ->add('zapisz', SubmitType::class, [
                'label' => 'wybierz',
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $magazyny = $data['Magazyny'];
            $artykuly = $magazyny->getArtykuly()->toArray();
        }

        return $this->render('Produkty/show.html.twig', [
            'form' => $form->createView(),
            'artykuly' => $artykuly,
            'magazyny' => $magazyny,
        ]);
    }

    #[Route('/add', name: 'app_dodajArt')]
    public function addArticle(Request $request, ManagerRegistry $doctrine, Security $security): Response
    {
        $artykul = new Artykul();
        $form = $this->createFormBuilder($artykul)
            ->add('Nazwa', ChoiceType::class, [
                'choices' => $this->getNazwyArtykulow($doctrine),
                'placeholder' => '-- wybierz nazwę --',
                'required' => true,
            ])
            ->add('JednostkaMiary', null, ['required' => true])
            ->add('IloscPrzyjeta', null, ['required' => true])
            ->add('VAT', null, ['required' => true])
            ->add('Cena', null, ['required' => true])
            ->add('magazyny', EntityType::class, [
                'class' => Magazyny::class,
                'choices' => $this->getUser()->getUserMagazyn()->toArray(),
                'choice_label' => 'nazwa',
                'required' => true,
            ])
            ->add('zapisz', SubmitType::class, [
                'label' => 'Zapisz',
            ])
            ->getForm();
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $artykul = $form->getData();
    
            $nazwa = $form->get('Nazwa')->getData();
            $artykul->setNazwa($nazwa);
    
            $magazynId = $form->get('magazyny')->getData();
            $magazyn = $doctrine->getRepository(Magazyny::class)->find($magazynId);
            $artykul->setMagazyny($magazyn);
    
            $entityManager = $doctrine->getManager();
            $entityManager->persist($artykul);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_user');
        }
    
        return $this->render('Produkty/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/wydaj', name: 'app_wydaj')]
    public function wydajArticle(Request $request, ManagerRegistry $doctrine, Security $security): Response
    {
        $form = $this->createFormBuilder()
            ->add('Nazwa', ChoiceType::class, [
                'choices' => $this->getNazwyArtykulow($doctrine),
                'placeholder' => '-- wybierz nazwę --',
                'required' => true,
            ])
            ->add('IloscWydana')
            ->add('magazyny', EntityType::class, [
                'class' => Magazyny::class,
                'choices' => $this->getUser()->getUserMagazyn()->toArray(),
                'choice_label' => 'nazwa',
                'required' => true,
            ])
            ->add('zapisz', SubmitType::class, [
                'label' => 'Zapisz',
            ])
            ->getForm();
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $nazwa = $form->get('Nazwa')->getData();
            $iloscWydana = $form->get('IloscWydana')->getData();
            $magazynId = $form->get('magazyny')->getData();
    
            $artykul = $doctrine->getRepository(Artykul::class)->findOneBy(['Nazwa' => $nazwa, 'magazyny' => $magazynId]);
            $artykul->setIloscWydana($iloscWydana);
    
            $entityManager = $doctrine->getManager();
            $entityManager->persist($artykul);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_user');
        }
    
        return $this->render('Produkty/wydaj.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    private function getNazwyArtykulow(ManagerRegistry $doctrine): array
    {
        $artykuly = $doctrine->getRepository(Artykul::class)->findAll();
        $nazwyArtykulow = [];
    
        foreach ($artykuly as $artykul) {
            $nazwyArtykulow[$artykul->getNazwa()] = $artykul->getNazwa();
        }
    
        return $nazwyArtykulow;
    }
}
