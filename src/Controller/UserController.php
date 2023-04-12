<?php

namespace App\Controller;

use App\Entity\Artykul;
use App\Entity\Magazyny;
use App\Form\ArtykulType;
use App\Repository\ArtykulRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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

    #[Route('/add', name: 'app_add_article')]
    public function addArticle(Request $request, ManagerRegistry $doctrine, Security $security): Response
    {
        $artykul = new Artykul();
        $form = $this->createForm(ArtykulType::class, $artykul);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $artykul = $form->getData();

            $user = $security->getUser();
            $magazyn = $user->getUserMagazyn();
            $artykul->setMagazyn($magazyn);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($artykul);
            $entityManager->flush();

            return $this->redirectToRoute('app_user');
        }

        return $this->render('Produkty/add.html.twig', [
            'form' => $form->createView(),
        ]);
        }
}
