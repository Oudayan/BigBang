<?php
/**
 * @file     BaseController.php
 * @author   Oudayan Dutta
 * @version  1.0
 * @date     31 janvier 2018
 * @brief    Controlleur parent 
 * @details  Fonctions abstracte process pour les cases, displayView pour afficher les vues et getDAO pour la connection à la base de donnée
 */

	abstract class BaseController {

		// la méthode qui sera appelée par le routeur
		public abstract function process(array $params);

        //
        protected function displayView($viewName, $data = null) {
            // Inclure le header pour chaque vue
            // include(ROOT . "views/header.twig");

            // If its only a string, we just include this one
            if (is_string($viewName)) {
                $viewPath = ROOT . "views/" . $viewName . ".html";
                    if (file_exists($viewPath)){
                    include($viewPath);
                }
            }
            // If it's an array, we include every array position (containing a string)
            else if (is_array($viewName)) {
                foreach ($viewName as $contentPath) {
                    $viewPath = ROOT . "views/" . $contentPath . ".html";
                    if (file_exists($viewPath)){
                        include($viewPath);
                    }
                    else {
                        trigger_error("Erreur 404! La vue $viewPath n'existe pas.");
                    }
                }
            }
            // Inclure le footer pour chaque vue
            // include(ROOT . "views/hooter.twig");
        }        
        
		protected function getDAO($modelName) {
			$class = $modelName . "Model";

			if (class_exists($class)) {
				//on fait une connexion à la base de données
				$DB = DBFactory::getDB(DBTYPE, DBNAME, HOST, USERNAME, PWD);

				//on crée une instance de la classe Modele_$class
				$modelObject = new $class($DB);
				if ($modelObject instanceof BaseDAO) {
					return $modelObject;
				}
				else {
					trigger_error("Le modèle n'est pas conforme.");
				}
			}
		}

	}

?>