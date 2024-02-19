<?php

namespace App\Controller;

use App\Service\PanierService;
use PhpParser\Node\Name;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route(
        path: '/{_locale}/panier',
        name: 'app_panier',
        requirements: ['_locale' => '%app.supported_locales%'],
        defaults: ['_locale' => 'fr']
    )]
    public function index(PanierService $panierService): Response
    {
        return $this->render('panier/index.html.twig', [
            'panier' => $panierService->getContenu(),
        ]);
    }

    #[Route(
        path: '/{_locale}/panier/ajouter/{idProduit}/{quantite}',
        name: 'app_panier_ajouter',
        requirements: ['_locale' => '%app.supported_locales%'],
        defaults: ['_locale' => 'fr']
    )]
    public function ajouter(PanierService $panierService, int $idPorduit, int $quantite)
    {
        $panierService->ajouterProduit($idPorduit, $quantite);
        
    }
}
