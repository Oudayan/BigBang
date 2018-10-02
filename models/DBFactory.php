<?php
/**
 * @file     DBFactory.php
 * @author   Oudayan Dutta
 * @version  1.0
 * @date     31 janvier 2018
 * @brief    Manufacture de base de données
 * @details  Définit les parmètres requis poue des connextions à différents types de serveurs
 */

	class DBFactory {

		public static function getDB($typeBD, $dbName, $host, $user, $pwd) {
			if ($typeBD == "mysql") {
				$DB = new PDO("mysql:host=$host;dbname=$dbName", $user, $pwd);
			}
			else if ($typeBD == "oracle") {
				$DB = new PDO("oci:host=$host;dbname=$dbName", $user, $pwd);		
			}
			//else if...
			else
				trigger_error("Le type de BD spécifié n'est pas supporté.");
			
			$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$DB->exec("SET NAMES 'utf8'");
			return $DB;			
		}

    }

?>

