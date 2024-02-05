<?php

namespace App\Controller;

use App\Service\BoutiqueService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoutiqueController extends AbstractController
{
    #[Route(
        path:'/boutique', 
        name: 'app_boutique'
    )]

    public function index(BoutiqueService $boutique) : Response
    {
        $categories = $boutique->findAllCategories();

        return $this->render("boutique/index.html.twig", ["categories" => null]);
    }

    #[Route(
        path: '/rayon/{idCategorie}', 
        name: 'app_boutique_rayon'
    )]
    public function rayon(int $idCategorie){
        
    }
}