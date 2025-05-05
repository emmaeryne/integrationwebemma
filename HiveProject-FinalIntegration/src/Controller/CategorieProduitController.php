<?php

namespace App\Controller;

use App\Entity\CategorieProduit;
use App\Form\CategorieProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorie/produit')]
class CategorieProduitController extends AbstractController
{
    #[Route('/', name: 'categorie_produit_index', methods: ['GET'])]//cette route accepte uniquement les requêtes HTTP GET
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categorieProduits = $entityManager//doctine 
            ->getRepository(CategorieProduit::class)//"Donne-moi tous les enregistrements de la table categorie_produit."
            ->findAll();// méthode qui retourne toutes les lignes de la table sous forme d’objets CategorieProduit

        return $this->render('categorie_produit/index.html.twig', [//index.html.twig
            'categorie_produits' => $categorieProduits,//On lui envoie la variable categorie_produits (le tableau d’objets)
        ]);
    }

    #[Route('/new', name: 'categorie_produit_new', methods: ['GET', 'POST'])]//	Affiche le formulaire vide
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorieProduit = new CategorieProduit();//On crée un nouvel objet vide CategorieProduit
        $form = $this->createForm(CategorieProduitType::class, $categorieProduit);//On crée un formulaire Symfony lié à cet objet
        $form->handleRequest($request);// remplir l’objet avec les données envoyées par l’utilisateur 

        if ($form->isSubmitted()) {//Cette condition est vraie quand l’utilisateur a cliqué sur "Soumettre"
            if ($form->isValid()) {//Ensuite, on vérifie s’il est valide
                // Traitement du fichier image
                /** @var UploadedFile|null $file */
                $file = $form->get('image')->getData();
                if ($file instanceof UploadedFile) {
                    $categorieProduit->setImage(file_get_contents($file->getPathname()));//On lit le contenu du fichier avec file_get_contents()
                }

                $entityManager->persist($categorieProduit);
                $entityManager->flush();// Doctrine sauvegarde l’objet dans la base (INSERT)

                // Retour JSON si requête AJAX
                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse(
                        ['message' => 'Catégorie créée avec succès.'],
                        Response::HTTP_OK
                    );
                }

                return $this->redirectToRoute('categorie_produit_index', [], Response::HTTP_SEE_OTHER);
            } else {
                // Récupération des messages d'erreur
                $errors = [];
                foreach ($form->getErrors(true) as $error) {
                    $errors[] = $error->getMessage();
                }
                $errorMessage = implode(' ', $errors);

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse(
                        ['message' => $errorMessage],
                        Response::HTTP_BAD_REQUEST
                    );
                }
            }
        }

        return $this->render('categorie_produit/new.html.twig', [
            'categorie_produit' => $categorieProduit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'categorie_produit_show', methods: ['GET'])]
    public function show(CategorieProduit $categorieProduit): Response
    {
        return $this->render('categorie_produit/show.html.twig', [
            'categorie_produit' => $categorieProduit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieProduit $categorieProduit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieProduitType::class, $categorieProduit);
        $form->handleRequest($request);
//. Gestion de l’image (si nouvelle image envoyée)
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $file */
            $file = $form->get('image')->getData();
            if ($file instanceof UploadedFile) {
                $categorieProduit->setImage(file_get_contents($file->getPathname()));
            }
            $entityManager->flush();

            return $this->redirectToRoute('categorie_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie_produit/edit.html.twig', [
            'categorie_produit' => $categorieProduit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_produit_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieProduit $categorieProduit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $categorieProduit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorieProduit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categorie_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
