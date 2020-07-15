SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `media_type`;
INSERT INTO `media_type` VALUES (1,'Book',30),(2,'Film',7),(3,'Music',15);
TRUNCATE TABLE `state_of_media`;
INSERT INTO `state_of_media` VALUES (1,'mauvais état'),(2,'bon état'),(3,'neuf');
TRUNCATE TABLE `role`;
INSERT INTO `role` VALUES (1,'superadmin'),(2,'bibliothècaire'),(3,'webmaster');
SET FOREIGN_KEY_CHECKS = 1;