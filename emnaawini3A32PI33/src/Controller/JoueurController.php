<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\Users;
use App\Form\JoueurType;
use App\Repository\JoueurRepository;
use App\Service\AzureFaceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Equipe;

#[Route('/joueur')]
final class JoueurController extends AbstractController
{
    public function __construct(private AzureFaceService $azureFaceService) {}

    #[Route(name: 'app_joueur_index', methods: ['GET'])]
    public function index(JoueurRepository $joueurRepository): Response
    {
        return $this->render('joueur/index.html.twig', [
            'joueurs' => $joueurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_joueur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $joueur = new Joueur();
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedUserId = $form->get('id_user')->getData();

            $user = $entityManager->getRepository(Users::class)->find($selectedUserId);

            if ($user) {
                $joueur->setNom_joueur($user->getUsername());
            }

            $entityManager->persist($joueur);
            $entityManager->flush();

            return $this->redirectToRoute('app_joueur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('joueur/new.html.twig', [
            'joueur' => $joueur,
            'form' => $form,
        ]);
    }

    #[Route('/{id_joueur}', name: 'app_joueur_show', methods: ['GET'])]
    public function show(Joueur $joueur): Response
    {
        return $this->render('joueur/show.html.twig', [
            'joueur' => $joueur,
        ]);
    }

    #[Route('/{id_joueur}/edit', name: 'app_joueur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Joueur $joueur, EntityManagerInterface $entityManager): Response
    {
        // Create the form and bind it to the Joueur entity
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        // Handle form submission
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_joueur_index', [], Response::HTTP_SEE_OTHER);
        }

        // Render the form in the template
        return $this->render('joueur/edit.html.twig', [
            'joueur' => $joueur,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id_joueur}', name: 'app_joueur_delete', methods: ['POST'])]
    public function delete(Request $request, Joueur $joueur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$joueur->getId_joueur(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($joueur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_joueur_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/joueur/upload-csv', name: 'app_joueur_upload_csv', methods: ['GET', 'POST'])]
    public function uploadCsv(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $file = $request->files->get('csv_file');

            if ($file && $file->isValid()) {
                $csvData = file_get_contents($file->getPathname());
                $rows = array_map('str_getcsv', explode("\n", $csvData));
                $header = array_shift($rows);

                foreach ($rows as $row) {
                    if (count($row) < 6) {
                        continue; // Skip invalid rows
                    }

                    $data = array_combine($header, $row);

                    $joueur = new Joueur();
                    $joueur->setNomJoueur($data['nom_joueur']);
                    $joueur->setCin($data['cin']);
                    $joueur->setUrlPhoto($data['url_photo']);

                    // Set related entities (Equipe)
                    $equipe = $entityManager->getRepository(Equipe::class)->find($data['id_equipe']);
                    if ($equipe) {
                        $joueur->setEquipe($equipe);
                    }

                    // Directly set the user ID without loading the Users entity
                    if (isset($data['id_user'])) {
                        $joueur->setIdUser((int) $data['id_user']);
                    }

                    $entityManager->persist($joueur);
                }

                $entityManager->flush();

                $this->addFlash('success', 'CSV file uploaded successfully!');
                return $this->redirectToRoute('app_joueur_index');
            }

            $this->addFlash('error', 'Invalid file upload.');
        }

        return $this->render('joueur/upload_csv.html.twig');
    }
}
