<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PanierService;
use Doctrine\ORM\Mapping\Id;

#[Route('/{_locale}/panier', requirements: ['_locale' => '%app.supported_locales%'])]
class PanierController extends AbstractController
{
    #[Route('/', name: 'app_panier_index')]
    public function index(PanierService $panier): Response
    {
        
        return $this->render('panier/index.html.twig', [
            'contenu' => $panier->getContenu(),
            'nbProduit' => $panier->getNombreProduits(),
            'montant' => $panier->getTotal(),
        ]);
    }

    #[Route('/ajouter/{idProduit}/{quantite}', name: 'app_panier_ajouter', requirements: ['idProduit' => '\d+','quantite' => '\d+'])]
    public function ajouter(PanierService $panier, int $idProduit, int $quantite) : Response{
        
        $panier->ajouterProduit($idProduit, $quantite);
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/enlever/{idProduit}/{quantite}', name: 'app_panier_enlever', requirements: ['idProduit' => '\d+','quantite' => '\d+'])]
    public function enlever(PanierService $panier, $idProduit, $quantite) : Response{

        $panier->enleverProduit($idProduit, $quantite);
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/supprimer/{idProduit}', name: 'app_panier_supprimer', requirements: ['idProduit' => '\d+'])]
    public function supprimer(PanierService $panier, $idProduit) : Response{

        $panier->supprimerProduit($idProduit);
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/vider', name: 'app_panier_vider')]
    public function vider(PanierService $panier, ) : Response{

        $panier->vider();
        return $this->redirectToRoute('app_panier_index');
    }

    public function nombreProduits(PanierService $panier) : Response
    {
        return new Response($panier->getNombreProduits());
    }

}
