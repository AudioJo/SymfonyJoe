<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Chaton;
use App\Form\CategorieAjouterType;
use App\Form\ChatonsSupprimerType;
use App\Form\ChatonType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatonsController extends AbstractController
{
    /**
     * @Route("/chatons", name="app_chatons")
     */
    public function index(): Response
    {
        return $this->render('chatons/index.html.twig', [
            'controller_name' => 'ChatonsController',
        ]);
    }

    /**
     * @Route ("/chaton/ajouter",name="chatons_ajouter")
     */
    public function ajouterChaton(ManagerRegistry $doctrine, Request $request)
    {
        $chaton = new Chaton();
        // si on arrive la, c'est qu' on a trouvé une categorie
        //on va créée le formulaire avec  ( il sera remplie avec ses valeurs)
        $form = $this->createForm(ChatonType::class, $chaton);

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
            $em->persist($chaton);
            //generer l'insert
            $em->flush();

            //retour a l'accueil
            return $this->redirectToRoute("app_home");
        }

        return $this->render('chatons/ajouter.html.twig', [
            "formulaire" => $form->createView()
        ]);
    }

    /**
     * @Route ("/chatons/modifier/{id}",name="chatons_modifier")
     */
    public function modifierCategorie($id, ManagerRegistry $doctrine, Request $request)
    {
        //recuperer la catégorie dans la BDD
        $chaton = $doctrine->getRepository(Chaton::class)->find($id);

        //si on n'a rien trouvé -> 404
        if (!$chaton) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $id");
        }

        // si on arrive la, c'est qu' on atrouvé une categorie
        //on va créée le formulaire avec  ( il sera remplie avec ses valeurs)
        $form = $this->createForm(ChatonType::class, $chaton);

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
            $em->persist($chaton);
            //generer l'insert
            $em->flush();

            //retour a l'accueil
            return $this->redirectToRoute("app_home");
        }

        return $this->render('chatons/modifier.html.twig', [
            'chaton' => $chaton,
            "formulaire" => $form->createView()
        ]);
    }

    /**
     * @Route ("/chatons/supprimer/{id}",name="chatons_supprimer")
     */
    public function supprimerChatons($id, ManagerRegistry $doctrine, Request $request)
    {
        //recuperer la catégorie dans la BDD
        $chaton = $doctrine->getRepository(Chaton::class)->find($id);

        //si on n'a rien trouvé -> 404
        if (!$chaton) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $id");
        }

        // si on arrive la, c'est qu' on atrouvé une categorie
        //on va créée le formulaire avec  ( il sera remplie avec ses valeurs)
        $form = $this->createForm(ChatonType::class, $chaton);

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
            $em->remove($chaton);
            //generer l'insert
            $em->flush();

            //retour a l'accueil
            return $this->redirectToRoute("app_home");
        }

        return $this->render('chatons/supprimer.html.twig', [
            'categorie' => $chaton,
            "formulaire" => $form->createView()
        ]);
    }

    /**
     * @Route ("/chaton/{id}",name="chatons_afficher")
     */
    public function afficherChaton($id, ManagerRegistry $doctrine)
    {
        //recuperer la catégorie dans la BDD
        $categorie = $doctrine->getRepository(Categorie::class)->find($id);

        //si on n'a rien trouvé -> 404
        if (!$categorie) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $id");
        }

        return $this->render('chatons/afficher.html.twig', [
            'categories' => $categorie,
            "chatons" => $categorie->getChatons()
        ]);
    }
}
