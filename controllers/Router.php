<?php
/**
 * @file     Router.php
 * @author   Oudayan Dutta
 * @version  1.0
 * @date     31 janvier 2018
 * @brief    Route vers le bon controlleur 
 * @details  Route toute les requêtes URL (query string) au bon controlleur 
 */
	class Router {

		public static function route() {
			//obtenir le controleur qui devra traiter la requête.
			$queryString = $_SERVER["QUERY_STRING"];
			$ampersandPos = strpos($queryString, "&");

			if ($ampersandPos === FALSE) {
				$controller = $queryString;
            }
			else {
				$controller = substr($queryString, 0, $ampersandPos);
            }

			//si aucun controleur n'a été spécifié, mettre un controleur par défaut
			if ($controller == "") {
				$controller = "Presentations";
            }
			//chercher la classe avec le nom du controleur
			$class = $controller . "Controller";

			if (class_exists($class)) {
				//déclaration du controleur
				$controllerObject = new $class;
				if ($controllerObject instanceof Basecontroller) {
					$controllerObject->process($_REQUEST);
                }
				else {
					trigger_error("Controleur invalide.");
                }
			}
			else {
				trigger_error("Erreur 404! Le controleur $class n'existe pas.");
			}
		}

	}

?>