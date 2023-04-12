<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Magazyny;
use App\Form\PracownikType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_pracownik');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/dodajPracownika', name: 'app_pracownik')]
    public function pracownikMagazynu(Request $request, ManagerRegistry $doctrine)
{
    $form = $this->createFormBuilder()
    ->add('user', EntityType::class, [
        'class' => User::class,
        'choice_label' => 'username',
        'placeholder' => '-- wybierz użytkownika --',
        'required' => true,
    ])
    ->add('magazyn', EntityType::class, [
        'class' => Magazyny::class,
        'choice_label' => 'nazwa',
        'placeholder' => '-- wybierz magazyn --',
        'required' => true,
    ])
    ->add('zapisz', SubmitType::class, [
        'label' => 'Zapisz',
    ])
    ->getForm();

$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
    $data = $form->getData();
    $user = $data['user'];
    $magazyn = $data['magazyn'];

    $user->addUserMagazyn($magazyn);

    $entityManager = $doctrine->getManager();
    $entityManager->persist($user);
    $entityManager->flush();

    return $this->redirectToRoute('app_user');
}

return $this->render('admin/addPracownik.html.twig', [
    'form' => $form->createView(),
]);
}
}