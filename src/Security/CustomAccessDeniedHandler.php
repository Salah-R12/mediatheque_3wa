<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class CustomAccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
    	$baseUrl = $request->getRelativeUriForPath('/'); // chemin relatif pour revenir à la page d'accueil, par rapport à l'URL qui a lancé cette exception
    	$content = <<<HTML
<h1>Accès refusé</h1>
<p>Vous ne disposez pas des droits nécessaires pour accéder à cette page</p>
<a href="$baseUrl">Retour à la page d'accueil</a><br>
<a href="javascript: window.history.back();">Retour à la page précédente</a>
HTML;
    	return new Response($content, 403);
    }
}