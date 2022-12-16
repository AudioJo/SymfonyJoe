<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Chaton;
use App\Entity\Proprietaire;
use App\Form\ChatonType;
use App\Form\ProprietaireType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProprietairesController extends AbstractController
{
    /**
     * @Route("/proprietaires", name="app_proprietaires")
     */
    public function index(): Response
    {
        return $this->render('proprietaires/index.html.twig', [
            'controller_name' => 'ProprietairesController',
        ]);
    }

    /**
     * @Route ("/proprietaire/ajouter",name="proprietaire_ajouter")
     */
    public function ajouterProprietaire(ManagerRegistry $doctrine, Request $request)
    {
        $proprio = new Proprietaire();
        // si on arrive la, c'est qu' on a trouvé une categorie
        //on va créée le formulaire avec  ( il sera remplie avec ses valeurs)
        $form = $this->createForm(ProprietaireType::class, $proprio);

        //Gestion du retour du formulaire
        //on ajoute Request dans les parametres comme dans le projet precedent
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //le handLeRequest a rempli notre objet $categorie
            //qui n'est plus vite
            //pour sauvegarder, on va récupérer un entityManager de doctrine
            //qui comme son nom l'indique gere les entités
            $em = $doctrine->getManager();
            //on lui dit de la ajouter dans la BDD
            $em->persist($proprio);
            //generer l'insert
            $em->flush();

            //retour a l'accueil
            return $this->redirectToRoute("app_home");
        }

        return $this->render('proprietaires/ajouter.html.twig', [
            "formulaire" => $form->createView()
        ]);
    }

    /**
     * @Route ("/proprietaires/modifier/{id}",name="proprietaire_modifier")
     */
    public function modifierProprietaire($id,ManagerRegistry $doctrine, Request $request)
    {
        //recuperer la catégorie dans la BDD
        $proprio = $doctrine->getRepository(Proprietaire::class)->find($id);

        //si on n'a rien trouvé -> 404
        if (!$proprio) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id");
        }

        // si on arrive la, c'est qu' on atrouvé une categorie
        //on va créée le formulaire avec  ( il sera remplie avec ses valeurs)
        $form = $this->createForm(ProprietaireType::class, $proprio);

        //Gestion du retour du formulaire
        //on ajoute Request dans les parametres comme dans le projet precedent
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //le handLeRequest a rempli notre objet $categorie
            //qui n'est plus vite
            //pour sauvegarder, on va récupérer un entityManager de doctrine
            //qui comme son nom l'indique gere les entités
            $em = $doctrine->getManager();
            //on lui dit de la ranger dans la BDD
            $em->persist($proprio);
            //generer l'insert
            $em->flush();

            //retour a l'accueil
            return $this->redirectToRoute("app_home");
        }

        return $this->render('proprietaires/modifier.html.twig', [
            'proprieataires' => $proprio,
            "formulaire" => $form->createView()
        ]);
    }

    /**
     * @Route ("/proprietaires/supprimer/{id}",name="proprietaire_supprimer")
     */
    public function supprimerProprietaire($id,ManagerRegistry $doctrine, Request $request)
    {
        //recuperer la catégorie dans la BDD
        $proprio = $doctrine->getRepository(Proprietaire::class)->find($id);

        //si on n'a rien trouvé -> 404
        if (!$proprio) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id");
        }

        // si on arrive la, c'est qu' on atrouvé une categorie
        //on va créée le formulaire avec  ( il sera remplie avec ses valeurs)
        $form = $this->createForm(ProprietaireType::class, $proprio);

        //Gestion du retour du formulaire
        //on ajoute Request dans les parametres comme dans le projet precedent
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //le handLeRequest a rempli notre objet $categorie
            //qui n'est plus vite
            //pour sauvegarder, on va récupérer un entityManager de doctrine
            //qui comme son nom l'indique gere les entités
            $em = $doctrine->getManager();
            //on lui dit de la supprimer dans la BDD
            $em->remove($proprio);
            //generer l'insert
            $em->flush();

            //retour a l'accueil
            return $this->redirectToRoute("app_home");
        }

        return $this->render('proprietaires/supprimer.html.twig', [
            'proprietaires' => $proprio,
            "formulaire" => $form->createView()
        ]);
    }

    /**
     * @Route ("/proprietairesAfficher/",name="proprietaire_afficher")
     */
    public function afficherProprietaire( ManagerRegistry $doctrine)
    {
        //recuperer la catégorie dans la BDD
        $proprio = $doctrine->getRepository(Proprietaire::class)->findAll();

        //si on n'a rien trouvé -> 404
        if (!$proprio) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id");
        }

        return $this->render('proprietaires/afficher.html.twig', [
            'proprietaires' => $proprio,
        ]);
    }
}
