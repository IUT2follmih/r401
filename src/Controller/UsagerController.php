<?php

namespace App\Controller;

use App\Entity\Usager;
use App\Form\UsagerType;
use App\Repository\UsagerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsagerController extends AbstractController
{
    #[Route('/{_locale}/usager', name: 'app_usager', requirements: ['_locale' => '%app.supported_locales%'],)]
    public function index(UsagerRepository $usagerRepository): Response
    {
        return $this->render('usager/index.html.twig', [
            'usagers' => $usagerRepository->findAll(),
            'index' => '1',
        ]);
    }

    #[Route('/{_locale}/usager/new', name: 'app_usager_new', requirements: ['_locale' => '%app.supported_locales%'],)]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $usager = new Usager();
        $form = $this->createForm(UsagerType::class, $usager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($usager);
            $hashedPassword = $passwordHasher->hashPassword($usager, $usager->getPassword());
            $usager->setPassword($hashedPassword);
            $usager->setRoles(["ROLE_CLIENT"]);

            $entityManager->flush();

            return $this->redirectToRoute('app_usager', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('usager/new.html.twig', [
            'usager' => $usager,
            'form' => $form,
        ]);
    }
}