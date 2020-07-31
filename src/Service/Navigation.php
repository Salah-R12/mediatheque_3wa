<?php
namespace App\Service;

class Navigation{
	
	/**
	 * NOTE en français : les stratégies de "dynamisation" d'un menu et des droits d'accès ne sont pas homogènes d'une application à l'autre, ni d'un CMS à l'autre (exemple : Drupal != Wordpress)
	 * 		Cela dépend de plusieurs facteurs :
	 * 			1 - utilisation ou non d'une database (et si oui, de la structure ou de l'existence de tables dédiées à la gestion des éléments d'un menu et droits d'accès)
	 * 			2 - au design et à la logique voulus par un cahier des charges sur le fonctionnement d'un menu (par exemple, on peut opter ou non à un menu dépliable contenant une arborescence de sous-éléments)
	 * 			3 - bien sûr, des choix personnels des développeurs impliqués notamment s'il y a une séparation des tâches entre front et back end :
	 * 				en principe, s'il n'y a pas de gestion backend des menus ou des droits d'accès, et s'il n'y a pas beaucoup d'élements dans le menu (2 ou 3 liens),
	 * 				il n'y a pas la nécessité de développer un service Symfony comme celui-ci
	 * 
	 * 
	 * Returns all available links displayed into the main menu (navbar)
	 * 
	 * @todo Store links into database that could be managed from backend/admin session.
	 * 
	 * @param array $roles (optional) list of user roles
	 * 
	 * @return array
	 */
	public function getNavBarLinks(array $roles = null): array{
		/** Array schema:
		 * [
		 * 		// category
		 * 		{
		 * 			"title": "",
		 * 			"route_name": "",// Here, set the default route_name that is commonly the index' controller
		 * 			"roles": [1, 2, 3], // Set list of allowed role ids that can access this page, if null, this route is public
		 * 			//Eventually, set children
		 * 			"children":
		 * 				// Controller routes
		 * 				[
		 * 					{
		 * 						"title": "",
		 * 						"route_name": ""
		 * 					}, ...
		 * 				]
		 * 		},
		 * 		...
		 * ]
		*/
		$links = [
			[
				"title" => "Médiathèque 3WA", // Site name, link to home page
				"route_name" => 'dashboard',
				"roles" => null,
				"is_homepage" => true // this value MUST be set only ONCE (this define which route is the Home Page); TODO : also this should be set into the database
			],
			[
				"title" => "Livres",
				"route_name" => "book_index",
				"roles" => ['ROLE_USER', 'superadmin', 'bibliothècaire', 'webmaster']
			],
			[
				"title" => "Musiques",
				"route_name" => "music_index",
				"roles" => ['ROLE_USER', 'superadmin', 'bibliothècaire', 'webmaster']
			],
			[
				"title" => "Films",
				"route_name" => "film_index",
				"roles" => ['ROLE_USER', 'superadmin', 'bibliothècaire', 'webmaster']
			],
			[
				"title" => "Adhérents",
				"route_name" => "member_index",
				"roles" => ['superadmin', 'bibliothècaire', 'webmaster']
			],
			[
				"title" => "Équipe",
				"route_name" => "staff_index",
				"roles" => ['superadmin', 'bibliothècaire', 'webmaster'],
				// Here is an example of possible children
				"children" => [
					[
						"title" => "Rôles",
						"route_name" => "role_index",
						"roles" => ['superadmin']
					],
					[
						"title" => "Emprunts",
						"route_name" => "borrow_index",
						"roles" => ['superadmin', 'bibliothècaire']
					]
				]
			],
		];
		
		return $this->recursiveFilterOnLinksArray($links, $roles);
	}
	
	/**
	 * Filters links recursively, matching roles
	 * 
	 * @param array $links
	 * @param array $roles
	 * @return array
	 */
	private function recursiveFilterOnLinksArray(array $links, array $roles = null): array{
		foreach ($links as $ix => $item){
			if (!empty($item['children'])){
				$links[$ix]['children'] = $this->recursiveFilterOnLinksArray($item['children'], $roles);
			}
		}
		return array_filter($links, function($item) use ($roles){
			if (empty($item['roles'])){
				// S'il n'y a pas de role défini pour le lien (item), alors on retourne vrai quoi qu'il en soit
				return true;
			}
			if (empty($roles)){
				// Si c'est la liste des roles passées en paramètre qui est vide, alors que le lien a défini des droits, alors on retourne faux (l'item est retiré)
				return false;
			}
			// On vérifie dans la liste de roles donnée en paramètre qu'au moins l'un des roles de l'utilisateur "match" avec l'un des roles définis pour le lien
			foreach ($roles as $role){
				if (in_array($role, $item['roles'])){
					return true;
				}
			}
			// Aucune condition remplie, retourner faux
			return false;
		});
	}
}