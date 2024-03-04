<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Service\BoutiqueService;

// Service pour manipuler le panier et le stocker en session
class PanierService
{
    ////////////////////////////////////////////////////////////////////////////
    private $session;   // Le service session
    private $boutique;  // Le service boutique
    private $panier;    // Tableau associatif, la clé est un idProduit, la valeur associée est une quantité
                        //   donc $this->panier[$idProduit] = quantité du produit dont l'id = $idProduit
    const PANIER_SESSION = 'panier'; // Le nom de la variable de session pour faire persister $this->panier

    // Constructeur du service
    public function __construct(RequestStack $requestStack, BoutiqueService $boutique)
    {
        // Récupération des services session et BoutiqueService
        $this->boutique = $boutique;
        $this->session = $requestStack->getSession();
        // Récupération du panier en session s'il existe, init. à vide sinon
        $this->panier = $this->session->get(self::PANIER_SESSION, []);
    }

    // Renvoie le montant total du panier
    public function getTotal() : float
    {
      $total = 0;
      foreach($this->panier as $idProduit => $quantite){
        $produit = $this->boutique->findProduitById($idProduit);
        $total += $produit->prix * $quantite;
      }
      return $total;
    }

    // Renvoie le nombre de produits dans le panier
    public function getNombreProduits() : int
    {
      $nbProd = 0;
      foreach($this->panier as $value){
        $nbProd += $value;
      }
      return $nbProd;
    }

    // Ajouter au panier le produit $idProduit en quantite $quantite 
    public function ajouterProduit(int $idProduit, int $quantite = 1) : void
    {
      
      if(isset($this->panier[$idProduit])){
        $this->panier[$idProduit] = $this->panier[$idProduit] + $quantite;
      }
      else{
        
        $this->panier[$idProduit] = $quantite;
      }
      $this->refreshSession();

    }

    // Enlever du panier le produit $idProduit en quantite $quantite 
    public function enleverProduit(int $idProduit, int $quantite = 1) : void
    {
      if(isset($this->panier[$idProduit])){
        $this->panier[$idProduit] = $this->panier[$idProduit] - $quantite;
        if($this->panier[$idProduit] < 1)
          $this->supprimerProduit($idProduit);
      }
      $this->refreshSession();

    }

    // Supprimer le produit $idProduit du panier
    public function supprimerProduit(int $idProduit) : void
    {
      if(isset($this->panier[$idProduit]))
        unset($this->panier[$idProduit]);
      $this->refreshSession();

    }

    // Vider complètement le panier
    public function vider() : void
    {
      $this->panier = [];
      $this->refreshSession();
    }

    // Renvoie le contenu du panier dans le but de l'afficher
    //   => un tableau d'éléments [ "produit" => un objet produit, "quantite" => sa quantite ]
    public function getContenu() : array
    {
      $res = [];
      foreach($this->panier as $idProduit => $quantite){
        $produit = $this->boutique->findProduitById($idProduit);
        $res[] = ["produit" => $produit, "quantite" => $quantite];
      }
      return $res;
    }

    private function refreshSession(){
        $this->session->set(self::PANIER_SESSION, $this->panier);
    }

}