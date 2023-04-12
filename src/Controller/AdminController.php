<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Artykul;
use App\Entity\Magazyny;
use App\Form\ArtykulType;
use App\Form\MagazynType;
use App\Repository\MagazynyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/addmagazyn', name: 'app_addMagazyn')]
    public function nowyMagazyn(Request $request, ManagerRegistry $doctrine)
{
    $form = $this->createForm(MagazynType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $magazyn = $form->getData();

        $entityManager = $doctrine->getManager();
        $entityManager->persist($magazyn);
        $entityManager->flush();

        return $this->redirectToRoute('app_user');
    }

    return $this->render('admin/addMagazyn.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[IsGranted('ROLE_ADMIN')]
#[Route('/list', name: 'app_list')]
public function magazyny(MagazynyRepository $magazynyRepository): Response
{
    $magazyny = $magazynyRepository->findAll();

    return $this->render('admin/list.html.twig', [
        'magazyny' => $magazyny,
      
    ]);
}

#[IsGranted('ROLE_ADMIN')]
#[Route('/addArtykul', name: 'app_addArtykul')]
public function nowyArtykul(Request $request, ManagerRegistry $doctrine)
{
$form = $this->createForm(ArtykulType::class);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
    $artykul = $form->getData();

    $entityManager = $doctrine->getManager();
    $entityManager->persist($artykul);
    $entityManager->flush();

    return $this->redirectToRoute('app_list');
}

return $this->render('admin/nowyArtykul.html.twig', [
    'form' => $form->createView(),
]);

}

#[IsGranted('ROLE_ADMIN')]
#[Route('/addADM', name: 'app_dodajArtADM')]
public function addArticleADM(Request $request, ManagerRegistry $doctrine): Response
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
            'choices' => array_filter($doctrine->getRepository(Magazyny::class)->findAll()),
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

#[IsGranted('ROLE_ADMIN')]
#[Route('/wydajADM', name: 'app_wydajADM')]
public function wydajArticleADM(Request $request, ManagerRegistry $doctrine): Response
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
            'choices' => array_filter($doctrine->getRepository(Magazyny::class)->findAll()),
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