<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Categorie;
use App\Entity\Produit;


class BoutiqueController extends AbstractController
{
    #[Route('/{_locale}/boutique', name: 'app_boutique', requirements: ['_locale' => '%app.supported_locales%'],)]
    public function index(ManagerRegistry $doctrine): Response
    {   
        $categories = $doctrine->getRepository(Categorie::class)->findAll();
        if(!$categories){
            throw $this->createNotFoundException('Categorie pas trouvé');
        }
        return $this->render('boutique/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/{_locale}/boutique/rayon/{idCategorie}', name:'app_boutique_rayon', requirements: ['_locale' => '%app.supported_locales%'],)]
    public function rayon(int $idCategorie, ManagerRegistry $doctrine): Response{
       
        return $this->render('boutique/rayon.html.twig', [
            'produits'=> $doctrine->getRepository(Produit::class)->findByCategorie($idCategorie),
            'categorie' => $doctrine->getRepository(Categorie::class)->find($idCategorie),
        ]);
    }

    #[Route( 
        path: '/{_locale}/boutique/chercher/{recherche}', 
        name:'app_boutique_recherche',
        requirements: ['recherche' => '.+'], // regexp pour avoir tous les car, / compris  
        defaults: ['recherche' => '']
    )]
    public function cherche($recherche, ManagerRegistry $doctrine) : Response{

        $recherche = urldecode($recherche);

        return $this->render('boutique/chercher.html.twig', [
            'recherche' => $recherche,
            'produits' => $doctrine->getRepository(Produit::class)->search($recherche),
        ]);
    }
}
