<?php

namespace App\Controller;

use App\Service\BoutiqueService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoutiqueController extends AbstractController
{
    #[Route(
        path: '/{_locale}/boutique',
        name: 'app_boutique',
        requirements: ['_locale' => '%app.supported_locales%'],
        defaults: ['_locale' => 'fr']
    )]

    public function index(BoutiqueService $boutique): Response
    {
        $categories = $boutique->findAllCategories();

        return $this->render("boutique/index.html.twig", ["categories" => $categories]);
    }

    #[Route(
        path: '{_locale}/rayon/{idCategorie}',
        name: 'app_boutique_rayon',
        requirements: ['_locale' => '%app.supported_locales%'],

    )]
    public function rayon(BoutiqueService $boutique, int $idCategorie)
    {
        return $this->render('boutique/rayon.html.twig', [
            'items' => $boutique->findProduitsByCategorie($idCategorie),
            'categorie' => $boutique->findCategorieById($idCategorie),
        ]);
    }
}
