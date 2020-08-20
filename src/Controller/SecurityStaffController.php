<?php
namespace App\Controller;
use App\Entity\Staff;
use App\Form\ResetPassType;
use App\Repository\StaffRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityStaffController extends AbstractController
{
    /**
     *
     * @Route("/login/staff", name="staff_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, bool $included_as_block = false): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.staff.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'included_as_block' => $included_as_block
        ]);
    }

    /**
     *
     * @Route("/logout/staff", name="staff_logout")
     */
    public function logout()
    {
        // Renvoie vers la page principale de logout gérée par le controller "LoginController" (c'est cet URL /logout de base qui est configuré dans security.yaml pour être intercepté par Symfony et exécuter le logout du système)
        return $this->redirectToRoute('logout');
    }

    /**
     * @Route("/oubli-pass", name="app_forgotten_password")
     */
    public function oubliPass(Request $request, StaffRepository $staffRepository, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator
    ): Response
    {
        // On initialise le formulaire
        $form = $this->createForm(ResetPassType::class);

        // On traite le formulaire
        $form->handleRequest($request);

        // Si le formulaire est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les données
            $staffEmail = $form->get("email")->getData();

            // On cherche un utilisateur ayant cet e-mail
            $user = $staffRepository->findOneByEmail($staffEmail);

            // Si l'utilisateur n'existe pas
            if ($user === null) {
                // On envoie une alerte disant que l'adresse e-mail est inconnue
                $this->addFlash('danger', 'Cette adresse e-mail ne corespond à aucun utilisateur ');

                // On retourne sur la page de connexion
                return $this->redirectToRoute('dashboard');
            }
            // On génère un token
            $token = $tokenGenerator->generateToken();

            // On essaie d'écrire le token en base de données
            try {
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('dashboard');
            }

            // On génère l'URL de réinitialisation de mot de passe
            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            // On génère l'e-mail
            $message = (new \Swift_Message('Mot de passe oublié'))
                ->setFrom('no-reply@nouvelle-techno.fr')
                ->setTo($user->getEmail())
                ->setBody(
                    "Bonjour,<br><br>Une demande de réinitialisation de mot de passe 
                     a été effectuée pour le site Nouvelle-Techno.fr. 
                     Veuillez cliquer sur le lien suivant : 
                    <a href=".$url.">Réinitialisation de mot de passe</a>",
                    'text/html'
                );

            // On envoie l'e-mail
            $mailer->send($message);

            // On crée le message flash de confirmation
            $this->addFlash('message', 'E-mail de réinitialisation du mot de passe envoyé !');

            // On redirige vers la page de login
            return $this->redirectToRoute('login_index');

        }
        // On envoie le formulaire à la vue
        return $this->render('emails/forgotten_password.html.twig', ['emailForm' => $form->createView()]);
    }

    /**
     * @Route("/reset_pass/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder,StaffRepository $staffRepository)
    {
        // On cherche un utilisateur avec le token donné
        $staff = $staffRepository->findOneBy(['reset_token' => $token]);

        // Si l'utilisateur n'existe pas
        if ($staff === null) {
            // On affiche une erreur
            $this->addFlash('danger', 'Token Inconnu');
            return $this->redirectToRoute('dashboard');
        }

        // Si le formulaire est envoyé en méthode post
        if ($request->isMethod('POST')) {
            // On supprime le token
            $staff->setResetToken(null);

            // On chiffre le mot de passe
            $staff->setPassword($passwordEncoder->encodePassword($staff, $request->request->get('password')));

            // On stocke
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($staff);
            $entityManager->flush();

            // On crée le message flash
            $this->addFlash('message', 'Mot de passe mis à jour');

            // On redirige vers la page de connexion
            return $this->redirectToRoute('dashboard');
        }else {
            // Si on n'a pas reçu les données, on affiche le formulaire
            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }

    }

}
