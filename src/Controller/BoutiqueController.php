<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\BoutiqueService;


class BoutiqueController extends AbstractController
{
    #[Route('/{_locale}/boutique', name: 'app_boutique', requirements: ['_locale' => '%app.supported_locales%'],)]
    public function index(BoutiqueService $boutique): Response
    {   
        $categories = $boutique->findAllCategories();
        return $this->render('boutique/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/{_locale}/boutique/rayon/{idCategorie}', name:'app_boutique_rayon', requirements: ['_locale' => '%app.supported_locales%'],)]
    public function rayon(int $idCategorie, BoutiqueService $boutique): Response{
       
        return $this->render('boutique/rayon.html.twig', [
            'produits'=> $boutique->findProduitsByCategorie($idCategorie),
            'categorie' => $boutique->findCategorieById($idCategorie),
        ]);
    }

    #[Route( 
        path: '/{_locale}/boutique/chercher/{recherche}', 
        name:'app_boutique_recherche',
        requirements: ['recherche' => '.+'], // regexp pour avoir tous les car, / compris  
        defaults: ['recherche' => '']
    )]
    public function cherche($recherche, BoutiqueService $boutique) : Response{

        $recherche = urldecode($recherche);

        return $this->render('boutique/chercher.html.twig', [
            'recherche' => $recherche,
            'produits' => $boutique->findProduitsByLibelleOrTexte($recherche),
        ]);
    }
}
