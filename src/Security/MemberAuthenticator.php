<?php
namespace App\Security;
use App\Entity\Member;
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
 * @see \App\Controller\SecurityMemberController
 *
 */
class MemberAuthenticator extends AbstractFormLoginAuthenticator{
	use TargetPathTrait;

	/**
	 * Dans cette constante de classe, on déclare ici le lien de cet "Authenticator" avec la fonction de login de tel ou tel controller.
	 * Ici, le lien est fait avec la fonction "\App\Controller\SecurityMemberController::login"
	 *
	 * @see \App\Controller\SecurityMemberController::login
	 * @var string Controller function route name
	 */
	public const LOGIN_ROUTE = 'member_login';

	private $entityManager;

	private $urlGenerator;

	private $csrfTokenManager;

	/**
	 * Propriété ajoutée et mise à "false" par défaut, pour savoir dans quel contexte cette classe est utilisée, c'est-à-dire si on veut juste afficher la page de login ou
	 * si le formulaire est soumis et que la classe est instanciée pour exécuter le programme d'authentification du user.
	 * Donc, cette propriété sera passée à "true" dans la fonction "getCredentials"
	 *
	 * @var boolean
	 */
	private $formSubmitted = false;
	
	public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager){
		$this->entityManager = $entityManager;
		$this->urlGenerator = $urlGenerator;
		$this->csrfTokenManager = $csrfTokenManager;
	}

	public function supports(Request $request){
		return self::LOGIN_ROUTE === $request->attributes->get('_route') && $request->isMethod('POST');
	}

	public function getCredentials(Request $request){
		// Puisque cette fonction "getCredentials" est appelée, cela signifie que le formulaire a été "posté" (soumis) et l'application va procéder à l'authentification
		// avec les credentials saisis dans les inputs du formulaire (username et password)
		// Cela nous est utile dans la fonction "getLoginUrl", car en cas d'erreur (par exemple, mauvais password ou username inconnu) on veut rester sur la page de login spécifique au type d'utilisateur
		// et ne pas rebasculer vers la page principale de login qui présente les 2 formulaires de login car les messages d'erreur sont toujours affichés dans le premier block
		$this->formSubmitted = true;
		
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

		$user = $this->entityManager->getRepository(Member::class)->findOneBy([
			'username' => $credentials['username']
		]);

		if (!$user){
			// fail authentication with a custom error
			throw new CustomUserMessageAuthenticationException('Nom d\'utilisateur inconnu');
		}

		return $user;
	}

	public function checkCredentials($credentials, UserInterface $user){
		// On compare le password tapé dans le formulaire et le password en base de données (en plain text, donc, pour le moment, ce n'est pas RGPD compliant)
		// Pour rajouter un minimum de sécurité, on pourrait utiliser la fonction PHP "md5()" lorsqu'on enregistre un mdp (pourquoi pas dans le "setter" de la propriété "password" de l'entité Member)
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
		// NOTE FD: ici, on veut que lorsqu'on n'est pas encore connecté mais qu'on tente d'accéder à une page sécurisée (configurée dans security.yaml, dans la section "access_control")
		//			on soit renvoyé vers la page de login principale (celle de LoginController, et non pas celle de SecurityStaffController) afin de permettre à l'utilisateur d'avoir le choix
		//			de se connecter soit avec un profil member, soit un profil staff
		//			Mais lors de la soumission du formulaire, s'il y a une erreur, on veut afficher uniquement le formulaire qui a été soumis (et ignorer l'autre, donc)
		//			Pour faire cela, on a ajouté et déclaré une propriété de classe "$formSumitted = false"
		//			qui sera passé à "true" dans la fonction "getCredentials" (puisque cette fonction est exécutée au moment de la soumission du formulaire)
		$route_name = $this->formSubmitted ? self::LOGIN_ROUTE : 'login_index';
		return $this->urlGenerator->generate($route_name);
	}
}
