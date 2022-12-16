<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieAjouterType;
use App\Form\CategorieSupprimerType;
use App\Form\CategorieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        //création du formulaire d'ajout
        $categorie = new Categorie(); //on crée une categorie ville
        //On crée un fomulaire a partir de la classe CategorieType et de notre Objet vide
        $form = $this->createForm(CategorieType::class, $categorie);

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
            $em->persist($categorie);
            //generer l'insert
            $em->flush();
        }


        //Pour aller chercher les catégories, je vais utiliser un repository
        //Pour me servir de doctrine j'ajoute le parametres $doctrine a la mathode
        $repo = $doctrine->getRepository(Categorie::class);
        $categories = $repo->findAll();


        return $this->render('categories/index.html.twig', [
            'categories' => $categories,
            "formulaire" => $form->createView()
        ]);
    }

    /**
     * @Route ("/categorie/modifier/{id}",name="categorie_modifier")
     */
    public function modifierCategorie($id, ManagerRegistry $doctrine, Request $request)
    {
        //recuperer la catégorie dans la BDD
        $categorie = $doctrine->getRepository(Categorie::class)->find($id);

        //si on n'a rien trouvé -> 404
        if (!$categorie) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $id");
        }

        // si on arrive la, c'est qu' on atrouvé une categorie
        //on va créée le formulaire avec  ( il sera remplie avec ses valeurs)
        $form = $this->createForm(CategorieType::class, $categorie);

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
            $em->persist($categorie);
            //generer l'insert
            $em->flush();

            //retour a l'accueil
            return $this->redirectToRoute("app_home");
        }

        return $this->render('categories/modifier.html.twig', [
            'categorie' => $categorie,
            "formulaire" => $form->createView()
        ]);
    }

    /**
     * @Route ("/categorie/supprimer/{id}",name="categorie_supprimer")
     */
    public function supprimerCategorie($id, ManagerRegistry $doctrine, Request $request)
    {
        //recuperer la catégorie dans la BDD
        $categorie = $doctrine->getRepository(Categorie::class)->find($id);

        //si on n'a rien trouvé -> 404
        if (!$categorie) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $id");
        }

        // si on arrive la, c'est qu' on a trouvé une categorie
        //on va créée le formulaire avec  ( il sera remplie avec ses valeurs)
        $form = $this->createForm(CategorieSupprimerType::class, $categorie);

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
            $em->remove($categorie);
            //generer l'insert
            $em->flush();

            //retour a l'accueil
            return $this->redirectToRoute("app_home");
        }

        return $this->render('categories/supprimer.html.twig', [
            'categorie' => $categorie,
            "formulaire" => $form->createView()
        ]);
    }

}
