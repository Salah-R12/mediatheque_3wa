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
	 * @param int $role_id (optional) ID defined into table/entity "role"
	 * 
	 * @return array
	 */
	public function getNavBarLinks(int $role_id = null): array{
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
				"route_name" => 'dashboard', // There is not yet a real home page
				"roles" => null,
				"is_homepage" => true // this value MUST be set only ONCE (this define which route is the Home Page); TODO : also this should be set into the database
			],
			[
				"title" => "Livres",
				"route_name" => "book_index",
				"roles" => null, // TODO: define roles, for instance, it's public
			],
			[
				"title" => "Musiques",
				"route_name" => "music_index",
				"roles" => null
			],
			[
				"title" => "Films",
				"route_name" => "film_index",
				"roles" => null
			],
			[
				"title" => "Adhérents",
				"route_name" => "member_index",
				"roles" => null
			],
			[
				"title" => "Équipe",
				"route_name" => "staff_index",
				"roles" => null,
				// Here is an example of possible children
				"children" => [
					[
						"title" => "Rôles",
						"route_name" => "role_index",
						"roles" => null
					],
					[
						"title" => "Emprunts",
						"route_name" => "borrow_index",
						"roles" => null
					]
				]
			],
		];
		// TODO should define routes & roles into database, instead of hard links defined into this code, i.e. store values from previous array into the database
		// Nota: all routes are not listed here. Use Symfony command to see all all routes: ./bin/console debug:router
		
		$filteredLinks = [];
		if ($role_id){
			$filteredLinks = $this->recursiveFilterOnLinksArray($links, $role_id);
		} else
			$filteredLinks = $links;
		return $filteredLinks;
	}
	
	/**
	 * Filters links recursively
	 * 
	 * @param array $links
	 * @param int $role_id
	 * @return array
	 */
	private function recursiveFilterOnLinksArray(array $links, int $role_id): array{
		foreach ($links as $key => $item){
			if (!empty($item['children'])){
				$links[$key] = $this->recursiveFilterOnLinksArray($item['children'], $role_id);
			}
		}
		return array_filter($links, function($item) use ($role_id){
			if (empty($item['roles']))
				return true;
				return in_array($role_id, $item['roles']);
		});
	}
}