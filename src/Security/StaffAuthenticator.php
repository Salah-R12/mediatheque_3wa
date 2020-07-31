<?php
namespace App\Security;
use App\Entity\Staff;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 *
 * @see \App\Controller\SecurityStaffController
 *
 */
class StaffAuthenticator extends AbstractFormLoginAuthenticator{
	use TargetPathTrait;

	/**
	 * Dans cette constante de classe, on déclare ici le lien de cet "Authenticator" avec la fonction de login de tel ou tel controller.
	 * Ici, le lien est fait avec la fonction "\App\Controller\SecurityStaffController::login"
	 *
	 * @see \App\Controller\SecurityStaffController::login
	 * @var string Controller function route name
	 */
	public const LOGIN_ROUTE = 'staff_login';

	private $entityManager;

	private $urlGenerator;

	private $csrfTokenManager;

	public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager){
		$this->entityManager = $entityManager;
		$this->urlGenerator = $urlGenerator;
		$this->csrfTokenManager = $csrfTokenManager;
	}

	public function supports(Request $request){
		return self::LOGIN_ROUTE === $request->attributes->get('_route') && $request->isMethod('POST');
	}

	public function getCredentials(Request $request){
		$credentials = [
			'username' => $request->request->get('username'),
			'password' => $request->request->get('password'),
			'csrf_token' => $request->request->get('_csrf_token')
		];
		$request->getSession()->set(Security::LAST_USERNAME, $credentials['username']);

		return $credentials;
	}

	public function getUser($credentials, UserProviderInterface $userProvider){
		$token = new CsrfToken('authenticate', $credentials['csrf_token']);
		if (!$this->csrfTokenManager->isTokenValid($token)){
			throw new InvalidCsrfTokenException();
		}

		$user = $this->entityManager->getRepository(Staff::class)->findOneBy([
			'username' => $credentials['username']
		]);

		if (!$user){
			// fail authentication with a custom error
			throw new CustomUserMessageAuthenticationException('Username could not be found.');
		}

		return $user;
	}

	public function checkCredentials($credentials, UserInterface $user){
		// On compare le password tapé dans le formulaire et le password en base de données (en plain text, donc, pour le moment, ce n'est pas RGPD compliant)
		// Pour rajouter un minimum de sécurité, on pourrait utiliser la fonction PHP "md5()" lorsqu'on enregistre un mdp (pourquoi pas dans le "setter" de la propriété "password" de l'entité Staff)
		// puis on aurait ici plutôt : return (bool)(md5($credentials['password']) === $user->getPassword());
		return (bool)($credentials['password'] === $user->getPassword());
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey){
		if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)){
			return new RedirectResponse($targetPath);
		}
		
		// Si aucun "targetPath" n'est passé (c'est dans le cas où la personne n'a pas tenté d'accéder à une page sécurisée avant de basculer vers la page de login, le système n'a donc pas mémorisé une URL valide pour se rediriger automatiquement)
		// Alors on se redirige vers la dashboard par défaut
		return new RedirectResponse($this->urlGenerator->generate('dashboard'));
	}

	protected function getLoginUrl(){
		//return $this->urlGenerator->generate(self::LOGIN_ROUTE);

		// NOTE FD: ici, on veut que lorsqu'on n'est pas encore connecté mais qu'on tente d'accéder à une page sécurisée (configurée dans security.yaml, dans la section "access_control")
		//			on soit renvoyé vers la page de login principale (celle de LoginController, et non pas celle de SecurityStaffController) afin de permettre à l'utilisateur d'avoir le choix
		//			de se connecter soit avec un profil member, soit un profil staff
		return $this->urlGenerator->generate('login_index');
	}
}
