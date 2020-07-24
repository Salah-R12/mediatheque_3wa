/* Suppression de la colonne en doublon "media_type" puisque "media_type_id" existe déjà.

	Cette colonne en doublon est créée par Doctrine lorsqu'on utilise "DiscriminatorColumn".
	Exemple dans la classe d'entité "Media" :
 * @ORM\DiscriminatorColumn(name="media_type_id", type="integer")
 * @ORM\DiscriminatorMap({"0" = "Media", "1" = "Book", "2" = "Film", "3" = "Music"})

	Ci-dessus, on définit que c'est la colonne "media_type_id" qui sera le discriminant pour attribuer un type précis à la classe Media qui est étendue à "Book", "Film" et "Music".
	Doctrine se sert de la valeur de la colonne "media_type_id" pour définir si un Media doit prendre une instance de "Book", "Film" ou "Music". Comme il existe une table "media_type" et que Doctrine crée aussi
	une clé étrangère "media_type_id" dans la table "media", il va ensuite créer en principe de créer la colonne "media_type_id_id" pour définir ce "discriminator" et implicitement, il ne va pas "fusionner" le
	principe de clé étrangère et celui du discriminant.

	La présence éventuelle de la colonne "media_type" dans "media" s'explique par le fait qu'au cours du développement, le nom du "DiscriminatorColumn" avait été "media_type" tout court.
	On peut donc alors éventuellement avoir tantôt la colonne "media_type", tantôt la colonne "media_type_id_id" dans la table "media" et il faut donc appliquer un "drop" sur la colonne en question.
 */
ALTER TABLE media DROP media_type;
ALTER TABLE media DROP media_type_id_id;

/* Sous MySQL, REPLACE fait la même chose que INSERT, sauf qu'il permet également de faire un update si la ligne existe déjà dans la table
	cf. : UPSERT
 */
REPLACE INTO `media_type` VALUES (1,'Book',30),(2,'Film',7),(3,'Music',15);
REPLACE INTO `state_of_media` VALUES (1,'mauvais état'),(2,'bon état'),(3,'neuf');
REPLACE INTO `role` VALUES (1,'superadmin'),(2,'bibliothècaire'),(3,'webmaster');
REPLACE INTO `staff` VALUES (1,'Fabrice','Dant','fabrice.dant','admin','admin@gmail.com','123','123','13001','Marseille','0606060606'),(2,'Salah','Salah','salah.salah','admin','admin2@gmail.com','123','123','13001','Marseille','0606060606'),(3,'Alper','Alper','alper.alper','admin','admin@gmail.com','123','123','13001','Marseille','0606060606'),(4,'Gaetan','Mallet','gaetan.mallet','admin','admin3@gmail.com','123','123','13001','Marseille','0606060606');

/* Sous Postgresql, il faut spécifier comment gérer les cas en cas de conflit sur la contrainte (unique) de clé primaire : ici je dis de ne rien faire (donc, ça ne génèrera pas de message d'erreur)*/
INSERT INTO `media_type` VALUES (1,'Book',30),(2,'Film',7),(3,'Music',15) ON CONFLICT (id) DO NOTHING;
INSERT INTO `state_of_media` VALUES (1,'mauvais état'),(2,'bon état'),(3,'neuf') ON CONFLICT (id) DO NOTHING;
INSERT INTO `role` VALUES (1,'superadmin'),(2,'bibliothècaire'),(3,'webmaster') ON CONFLICT (id) DO NOTHING;
INSERT INTO `staff` VALUES
	(1,'Fabrice','Dant','fabrice.dant','admin','admin@gmail.com','123','123','13001','Marseille','0606060606'),
	(2,'Salah','Salah','salah.salah','admin','admin2@gmail.com','123','123','13001','Marseille','0606060606'),
	(3,'Alper','Alper','alper.alper','admin','admin@gmail.com','123','123','13001','Marseille','0606060606'),
	(4,'Gaetan','Mallet','gaetan.mallet','admin','admin3@gmail.com','123','123','13001','Marseille','0606060606')
 ON CONFLICT (id) DO NOTHING;
