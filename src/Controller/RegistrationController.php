<?php

namespace App\Controller;
use VictorPrdh\RecaptchaBundle\Services\ReCaptchaValidator;
use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Cloudinary\Cloudinary;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Process\Process;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private EntityManagerInterface $entityManager;

    public function __construct(EmailVerifier $emailVerifier, EntityManagerInterface $entityManager)
    {
        $this->emailVerifier = $emailVerifier;
        $this->entityManager = $entityManager; // Injection de l'EntityManager

    }

    #[Route('/register', name: 'app_register')]

    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        Cloudinary $cloudinary,
        UserRepository $userRepository
    ): Response {
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
                'api_key' => $_ENV['CLOUDINARY_API_KEY'],
                'api_secret' => $_ENV['CLOUDINARY_API_SECRET']
            ],
            'url' => [
                'analytics' => false
            ]
        ]);
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();
            $username=$form->get('nom')->getData();
    
            if ($imageFile) {
                // Get extension directly from original filename
                $originalExtension = pathinfo($imageFile->getClientOriginalName(), PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $originalExtension;
                $imagePath = $this->getParameter('kernel.project_dir') . '/public/uploads/' . $filename;
    
                // Verify extension
                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                if (!in_array(strtolower($originalExtension), $allowedExtensions)) {
                    $this->addFlash('error', 'Format de fichier non autorisé. Utilisez JPG ou PNG.');
                    return $this->redirectToRoute('app_register');
                }
    
                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads',
                    $filename
                );
    
                // Appel du script Python
                $pythonScript = $this->getParameter('kernel.project_dir') . '/public/python/face_detection.py';
                $command = escapeshellcmd("python3 $pythonScript " . escapeshellarg($imagePath));
                $output = shell_exec($command);
    
                if (str_contains($output, 'Un visage humain')) {
                    try {
                        $uploadResult = $cloudinary->uploadApi()->upload($imagePath, [
                            'folder' => 'user_uploads',
                            'resource_type' => 'image'
                        ]);
                        $user->setImage($uploadResult['secure_url']);
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Erreur lors de l\'upload de l\'image: ' . $e->getMessage());
                        return $this->redirectToRoute('app_register');
                    }
                $user->setPasswordHash(
            $userPasswordHasher->hashPassword(
                $user,
                $form->get('password_hash')->getData()
            ));
            $user->setUsername($username);
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->setVerificationCode($code);
            // Enregistrement utilisateur
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Inscription réussie ! Veuillez vérifier votre email.');
           
            return $this->redirectToRoute('app_user_login');



            }else {
                $this->addFlash('error', 'Aucun visage détecté sur la photo.');
                return $this->redirectToRoute('app_register');
            }

        }
    }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'recaptcha_site_key' => $_ENV['GOOGLE_RECAPTCHA_SITE_KEY'],
        ]);
    }
}
