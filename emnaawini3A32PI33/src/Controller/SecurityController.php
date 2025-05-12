<?php
namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Form\ProfileEditType;
use App\Form\CoachType;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Cloudinary\Cloudinary;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PhpParser\Node\Stmt\Else_;
use Stripe\BillingPortal\Session;
use Symfony\Component\HttpFoundation\JsonResponse;

class SecurityController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/register', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $encoder,   UserPasswordHasherInterface $passwordHasher): Response
    {
       
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
           
            $imageFile = $form->get('image')->getData();
       

            if ($imageFile) {
                try {
                    $uploadResult = $cloudinary->uploadApi()->upload($imageFile->getRealPath());
                    $user->setImage($uploadResult['secure_url']);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image: ' . $e->getMessage());
                    return $this->redirectToRoute('app_register');
                }
            }

            // Hash the password and persist the user
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword(
                $passwordHasher->hashPassword($user, $plainPassword)
            );

            // Make sure changes are persisted
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Inscription réussie !');
            return $this->redirectToRoute('app_login'); // Redirect to login after successful registration
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(SessionInterface $session): void
    {
        $session->clear();
   
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

 


   
    #[Route('welcomePage/verifierCompute', name: 'app_user_verifier')]
    public function verifier(Request $request, SessionInterface $session, EntityManagerInterface $em): Response
    {

        $otp1 = $request->request->get('otp1');
        $otp2 = $request->request->get('otp2');
        $otp3 = $request->request->get('otp3');
        $otp4 = $request->request->get('otp4');
        $otpGlobal = $otp1 . $otp2 . $otp3 . $otp4;
        $code = $session->get('code');
        if ($otpGlobal === $code) {
            $user = $em->getRepository(Users::class)->find($session->get('id'));
            if ($user) {
                $user->setIsActive(true);
                $em->flush();
            }
            $this->addFlash('error', 'CompteVerifié.');
            return $this->render('welcomePage/welcome.html.twig', [
                'nom' => $session->get('nom'),
                'prenom' => $session->get('prenom'),
            ]);
        }

        return $this->render('welcomePage/verifier.html.twig', [
            'nom' => 'bachir',
            'prenom' => 'bachir'
        ]);
    }
    #[Route('/profile', name: 'app_user_profile')]
    public function profile(Request $request, SessionInterface $session, EntityManagerInterface $em): Response
    {
         $userId = $session->get('id');
           // Get the user from database
           $user = $em->getRepository(Users::class)->find($userId);
           if (!$user) {
               return $this->redirectToRoute('app_login');
           }
           return $this->render('users/profile.html.twig', [
            'user' => $user
        ]);
    }
   
    #[Route('/{id}/editProfile', name: 'app_users_editProfile', methods: ['GET', 'POST'])]
    public function editProfile(
        Request $request,
        Users $user,
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        // Create Cloudinary instance
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

        $form = $this->createForm(ProfileEditType::class, $user);
        $form->handleRequest($request);
   
        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le mot de passe
            $currentPassword = $form->get('currentPassword')->getData();
            $newPassword = $form->get('newPassword')->getData(); // c’est le nouveau mot de passe (si les 2 correspondent)
           
            if ($currentPassword || $newPassword) {
                if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                    $this->addFlash('error', 'Mot de passe actuel incorrect.');
                    return $this->redirectToRoute('app_users_editProfile', ['id' => $user->getId()]);
                }
           
                if ($newPassword !== null) {
                    $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                    $user->setPassword($hashedPassword);
                    $this->addFlash('success', 'Mot de passe mis à jour avec succès.');
                }
            }
   
            // Upload image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                try {
                    $uploadResult = $cloudinary->uploadApi()->upload($imageFile->getRealPath());
                    $user->setImage($uploadResult['secure_url']);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'image : ' . $e->getMessage());
                    return $this->redirectToRoute('app_users_editProfile', ['id' => $user->getId()]);
                }
            }
   
            $entityManager->flush();

             $session->set('nom', $user->getUsername());
            $session->set('email', $user->getEmail());
            $session->set('image', $user->getImage());

            return $this->redirectToRoute('app_user_profile', [], Response::HTTP_SEE_OTHER);
        }
   
        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    //AdminGestion
#[Route('/admin/listUsers', name: 'app_listUsers')]
public function listUsers(EntityManagerInterface $entityManager): Response
{
    $users = $entityManager
        ->getRepository(Users::class)
        ->findAll();

    return $this->render('back/users/index.html.twig', [
        'users' => $users,
    ]);
}

#[Route('/admin/toggle-user-status/{id}', name: 'app_toggle_user_status')]
public function toggleUserStatus(Users $user, EntityManagerInterface $entityManager): JsonResponse
{
    $user->setIsActive(!$user->IsActive());
    $entityManager->flush();

    return new JsonResponse([
        'success' => true,
        'newStatus' => $user->IsActive()
    ]);

}



#[Route('/admin/add-coach', name: 'app_add_coach', methods: ['GET', 'POST'])]
public function addCoach(Request $request, EntityManagerInterface $entityManager): Response
{
    $user = new Users();
    $form = $this->createForm(CoachType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() ) {
        try {
            // Check if email already exists
            $existingUser = $entityManager->getRepository(Users::class)
                ->findOneBy(['email' => $user->getEmail()]);

            if ($existingUser) {
                $this->addFlash('error', 'This email is already registered in the system.');
                return $this->render('back/users/add_coach.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            // Continue with user creation if email is unique
            $generatedPassword = bin2hex(random_bytes(8));
            $user->setPassword(password_hash($generatedPassword, PASSWORD_DEFAULT));
            $user->setRole('COACH');
            $user->setIsActive(false);

            $entityManager->persist($user);
            $entityManager->flush();

            // Send email to coach with credentials
            $mail = new PHPMailer(true);
           
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'motezselmi.77@gmail.com';
            $mail->Password = 'tghu xktr pllb juwq';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('motezselmi.77@gmail.com', 'Hive Admin');
            $mail->addAddress($user->getEmail());

            $mail->isHTML(true);
            $mail->Subject = 'Your Coach Account Credentials';
            $mail->Body = "Welcome to Hive as a Coach!<br><br>
                         Your login credentials:<br>
                         Email: {$user->getEmail()}<br>
                         Password: {$generatedPassword}<br><br>
                         Please change your password after first login.";

            $mail->send();
           
            $this->addFlash('success', 'Coach account created successfully and credentials sent by email.');
            return $this->redirectToRoute('app_listUsers');
           
        } catch (Exception $e) {
            $this->addFlash('error', 'Error: ' . $e->getMessage());
            // Log the error for debugging
            error_log('Coach creation error: ' . $e->getMessage());
        }
    }

    return $this->render('back/users/add_coach.html.twig', [
        'form' => $form->createView(),
    ]);
}

}
