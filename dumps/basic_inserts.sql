/* Suppression de la colonne en doublon "media_type" puisque "media_type_id" existe déjà */
ALTER TABLE media DROP media_type;

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
