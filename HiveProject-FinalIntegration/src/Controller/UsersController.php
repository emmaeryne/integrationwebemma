<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\CoachType;

use App\Form\UsersType;
use App\Form\login;
use App\Form\ProfileEditType;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Service\FaceRecognitionService;
use App\Repository\UserRepository;
use Cloudinary\Cloudinary;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PhpParser\Node\Stmt\Else_;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class UsersController extends AbstractController
{
    #[Route('/afficher', name: 'app_users_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager
            ->getRepository(Users::class)
            ->findAll();

        return $this->render('users/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/new', name: 'app_users_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('users/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_users_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Users $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProfileEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload if provided
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
               

            }
            $entityManager->flush();

            return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('users/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_users_delete', methods: ['POST'])]
    public function delete(Request $request, Users $user, EntityManagerInterface $entityManager,UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
    }

    //login
    #[Route('/', name: 'app_user_login', methods: ['GET', 'POST'])]
    public function logIn(Request $request, UserRepository $userRepository, SessionInterface $session): Response
    {  
        // Check for existing persistent session
        //$persistentCookie = $request->cookies->get('persistent_session');
       // if ($persistentCookie && !$session->has('id')) {
           // $userData = json_decode($persistentCookie, true);
            if (/*$userData &&*/ isset($userData['email']) && isset($userData['password'])) {
                $user = $userRepository->ChercherUserSelonEmailAndPassword($userData['email'], $userData['password']);
                if ($user && $user->getIsActive()) {
                    // Set session data
                    $session->set('nom', $user->getNom());
                    $session->set('prenom', $user->getPrenom());
                    $session->set('email', $user->getEmail());
                    $session->set('role', $user->getRole());
                    $session->set('image', $user->getImage());
                    $session->set('id', $user->getId());

                    // Redirect based on role
                    if ($user->getRole() == 'Admin') {
                        return $this->redirectToRoute('admin_dashboard');
                    } elseif (in_array($user->getRole(), ['ROLE_USER', 'ROLE_COACH'])) {
                        return $this->render('welcomePage/welcome.html.twig', [
                            'nom' => $user->getNom(),
                            'prenom' => $user->getPrenom(),
                        ]);
                    }
                }
            }
      // }

        // Regular login form handling continues here
        $loginForm = $this->createForm(login::class);
        $loginForm->handleRequest($request);

        if ($loginForm->isSubmitted()) {
            $email = $loginForm->get('email')->getData();
            $password = $loginForm->get('password_hash')->getData();

            if ($email && $password) {
                $user = $userRepository->ChercherUserSelonEmailAndPassword($email, $password);
                if ($user !== null) {
                    // Set session data
                    $session->set('nom', $user->getNom());
                    $session->set('prenom', $user->getPrenom());
                    $session->set('email', $user->getEmail());
                    $session->set('role', $user->getRole());
                    $session->set('image', $user->getImage());
                    $session->set('id', $user->getId());
                    $session->set('prenom', $user->getPrenom());
                    $session->set('user_id', $user->getPrenom());

                    

                    // Create persistent session cookie
                  /*  $response = new Response();
                    $sessionData = [
                        'user_id' => $user->getId(),
                        'email' => $user->getEmail(),
                        'password' => $password
                    ];
                    
                    /*$cookie = Cookie::create('persistent_session')
                        ->withValue(json_encode($sessionData))
                        ->withExpires(new \DateTime('+30 days'))
                        ->withHttpOnly(true)
                        ->withSecure(true)
                        ->withPath('/');
                    
                    $response->headers->setCookie($cookie);
                    $response->send();*/

                    $isActive = $user->getIsActive() ?? false;
                    if ($isActive === true) {
                    if($user->getRole()=='Admin')
                    {
                        return $this->redirectToRoute('app_dashboard');

                    }elseif($user->getRole()=='ROLE_USER')
                    {
                        $session->set('nom', $user->getNom());
                        $session->set('prenom', $user->getPrenom());
                        $session->set('email', $user->getEmail());
                        $session->set('role', $user->getRole());
                        $session->set('image', $user->getImage());

                        return $this->render('welcomePage/welcome.html.twig', [
                            'nom' => $user->getNom(),
                            'prenom' => $user->getPrenom(),
                        ]);    
                    }elseif($user->getRole()=='ROLE_COACH'){
                        $session->set('nom', $user->getNom());
                        $session->set('prenom', $user->getPrenom());
                        $session->set('email', $user->getEmail());
                        $session->set('role', $user->getRole());
                        $session->set('image', $user->getImage());

                        return $this->render('welcomePage/welcome.html.twig', [
                            'nom' => $user->getNom(),
                            'prenom' => $user->getPrenom(),
                        ]);      
                    }
                    else
                    {
                        $this->addFlash('loginEroor', '🚫 Compte Baneed');
                    }
                }else {
                        $this->addFlash('loginEroor', 'Compte Non Validé');
                        $code = $this->generateOtp();
                        $this->sendEmail($user->getEmail(), $code);
                        $session->set('code', $code);
                        return $this->redirectToRoute('app_user_verifier');
                    }
                } else {
                    $this->addFlash('loginEroor', 'Identifiants incorrects.');
                }
            } else {
                $this->addFlash('loginEroor', 'Identifiants incorrects.');
            }
            
        }

        return $this->render('users/login.html.twig', [
            'loginForm' => $loginForm->createView(),
        ]);
    }



    private function generateOtp(): string
    {
        $otp = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        return $otp;
    }


    #[Route('/sendEmail', name: 'app_user_sendEmail', methods: ['POST'])]
    public function sendEmail(string $email, string $code): JsonResponse
    {
        // Initialize PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF; // Changed to OFF for production, use DEBUG_SERVER for testing
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'motezselmi.77@gmail.com';
            $mail->Password = 'tghu xktr pllb juwq';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Changed to STARTTLS for port 587
            $mail->Port = 587;

            // Important security note:
            // Consider using app-specific password or moving credentials to .env file
            // $mail->Password = $_ENV['GMAIL_APP_PASSWORD'];

            // Recipients
            $mail->setFrom('motezselmi.77@gmail.com', 'hive'); // Changed to actual sender
            $mail->addAddress($email); // Using the parameter passed to the function

            // Uncomment if you need these
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            // Content
            $mail->isHTML(false);
            $mail->Subject = 'Verifier Compte By HIVE';
            $mail->Body = 'Voici votre code : [' . $code . ']' . "\r\n\r\n" .
                'Si vous avez des questions ou besoin d\'assistance supplementaire, n\'hesitez pas à nous contacter.' . "\r\n\r\n" .
                'Cordialement;';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            $mail->send();
            return new JsonResponse(['status' => 'success', 'message' => 'Email sent successfully']);

        } catch (Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Message could not be sent',
                'error' => $mail->ErrorInfo
            ], 500);
        }

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


//logOut
    #[Route('/logout', name: 'app_user_logout')]
    public function logout(Request $request, SessionInterface $session, EntityManagerInterface $em): Response
    {
$session->clear();

        // Clear the persistent session cookie
        //$response = new Response();
        //$response->headers->clearCookie('persistent_session');
        //$response->send();
        return $this->redirectToRoute('app_user_login');
    
    }


//front 
//PROFILE
#[Route('/profile', name: 'app_user_profile')]
public function profile(Request $request, SessionInterface $session, EntityManagerInterface $em): Response
{
     // Get the user ID from session
     $userId = $session->get('id');
     if (!$userId) {
         return $this->redirectToRoute('app_user_login');
     }
       // Get the user from database
       $user = $em->getRepository(Users::class)->find($userId);
       if (!$user) {
           return $this->redirectToRoute('app_user_login');
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
    Cloudinary $cloudinary,
    SessionInterface $session,
    UserPasswordHasherInterface $passwordHasher
): Response {
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
                $user->setPasswordHash($hashedPassword);
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

        // Mettre à jour les données de session
        $session->set('nom', $user->getNom());
        $session->set('prenom', $user->getPrenom());
        $session->set('email', $user->getEmail());
        $session->set('image', $user->getImage());

        return $this->redirectToRoute('app_user_profile', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('users/edit.html.twig', [
        'user' => $user,
        'form' => $form,
    ]);
}


    //ResetPassword
    // Update the route name from 'app_user_resetPassword' to 'app_forgot_password_request'
    #[Route('/userReset/resetPassword', name: 'app_forgot_password_request')]
    public function resetPassword(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $em->getRepository(Users::class)->findOneBy(['email' => $email]);

            if ($user) {
                // Generate reset token
                $token = bin2hex(random_bytes(32));
                $user->setResetToken($token);
                $user->setPasswordRequestedAt(new \DateTimeImmutable());
                $em->flush();

                // Send email with reset link
                $mail = new PHPMailer(true);
                try {
                    // Server settings
                    $mail->SMTPDebug = SMTP::DEBUG_OFF;
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'motezselmi.77@gmail.com';
                    $mail->Password = 'tghu xktr pllb juwq';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('motezselmi.77@gmail.com', 'Hive Security');
                    $mail->addAddress($email);

                    // Content
                    $resetUrl = $this->generateUrl('app_reset_password', ['token' => $token], 0);
                    $mail->isHTML(true);
                    $mail->Subject = 'Password Reset Request';
                    $mail->Body = $this->renderView('reset_password/email.html.twig', [
                        'resetUrl' => $resetUrl
                    ]);

                    $mail->send();
                    $this->addFlash('success', 'If an account exists with this email, you will receive a password reset link shortly.');
                } catch (Exception $e) {
                    $this->addFlash('error', 'There was a problem sending the email.');
                }
            } else {
                // Don't reveal whether a user account exists or not
                $this->addFlash('success', 'If an account exists with this email, you will receive a password reset link shortly.');
                return $this->redirectToRoute('app_user_login');
            }

            return $this->redirectToRoute('app_user_login');
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    #[Route('userReset/reset-password/{token}', name: 'app_reset_password')]
    public function resetPasswordConfirm(Request $request, string $token, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Find user by reset token
        $user = $em->getRepository(Users::class)->findOneBy(['resetToken' => $token]);

        if (!$user) {
            $this->addFlash('error', 'Invalid reset password token.');
            return $this->redirectToRoute('app_user_login');
        }

        // Check if token is expired (1 hour validity)
        if (!$user->getPasswordRequestedAt() || 
            $user->getPasswordRequestedAt()->getTimestamp() + 3600 < time()) {
            $this->addFlash('error', 'The password reset link has expired.');
            return $this->redirectToRoute('app_user_login');
        }

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the new password
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            // Update user password and clear reset token
            $user->setPasswordHash($hashedPassword); // Changed from setPassword to setPasswordHash
            $user->setResetToken(null);
            $user->setPasswordRequestedAt(null);

            $em->flush();

            $this->addFlash('success', 'Your password has been reset successfully.');
            return $this->redirectToRoute('app_user_login');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView()
        ]);
    }
    #[Route('/api/face', name: 'compare_face', methods: ['POST','GET'])]
    public function go(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('users/compare_face.html.twig');
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
    $user->setIsActive(!$user->getIsActive());
    $entityManager->flush();

    return new JsonResponse([
        'success' => true,
        'newStatus' => $user->getIsActive()
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
            $user->setPasswordHash(password_hash($generatedPassword, PASSWORD_DEFAULT));
            $user->setRole('ROLE_COACH');
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
