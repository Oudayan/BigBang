<?php
/**
 * @file     CategoriesController.php
 * @author   Oudayan Dutta
 * @version  1.0
 * @date     31 janvier 2018
 * @brief    Controlleur pour les catégories
 * @details  
 */

	class CategoriesController extends BaseController {

		public function process(array $params) {

            // Chercher le modèle des Categories
            $categoriesModel = $this->getDAO("Categories");
            // Chercher le modèle des Presentations
            $presentationsModel = $this->getDAO("Presentations");

			// Si le paramètre action existe
			if (isset($params["action"])) {

				// Switch en fonction de l'action qui nous est envoyée
				switch($params["action"]) {
					
                    // Affichage du menu select des categories
					case "displayCategories" :
                        $categories = $categoriesModel->getAllCategories();
                        echo "<option value='0' selected disabled>Veuillez choisir une catégorie</option>";
                        foreach ($categories as $category) {
                            echo "<option value=" . $category->Id . ">" . $category->Description . "</option>";
                        }
                        break;

                    // Affichage de la description de la category dans l'input NewCategory
					case "displayCategory" :
                        if (isset($params["CategoryId"]) && $params["CategoryId"] != "") {
                            $category = $categoriesModel->getCategoryById($params["CategoryId"]);
                            echo $category->Description;
                        }
                        break;

                    // Sauvegarde et modification d'une catégorie
					case "saveCategory" :
						if (isset($params["CategoryName"]) && $params["CategoryName"] != "") {
                            if (!isset($params["CategoryId"]) || $params["CategoryId"] == "") {
                                $id = 0;
                            }
                            else {
                                $id = $params["CategoryId"];
                            }
                            $SortOrder = $categoriesModel->getCategoriesCount();
                            $newCategory = new Category($id, $params["CategoryName"], ++$SortOrder["NbCategories"]);
                            $result = $categoriesModel->saveCategory($newCategory);
                            $this->showCategories();
						}
						else {	
							$this->showCategories();
						}
						break;

                    // Suppression d'une catégorie
                    case "confirmDeleteCategory" :
						if (isset($params["CategoryId"])) {
							$errors = $this->validateCategoryId($params["CategoryId"]);
							if (!$errors) {
                                // Vérifier si des Presentations existent pour ce Category
                                $presentationsByCategory = $presentationsModel->getPresentationsByCategory($params["CategoryId"]);
                                // Compter le nombre de Presentations et construire un array des Presentations à supprimer
                                $count = $presentationsModel->getPresentationsCount($params["CategoryId"]);
                                if ($count["NbPresentations"] < 1) {
                                    echo "Êtes-vous sûr de vouloir supprimer cette catégorie&nbsp;?";
                                }
                                else {
                                    if ($count["NbPresentations"] == 1) {
                                        echo "La présentation suivante fait partie de cette catégorie&nbsp;:";
                                    }
                                    else {
                                        echo "Les présentations suivantes font partie de cette catégorie&nbsp;:";
                                    }
                                    echo "<ul>";
                                    foreach ($presentationsByCategory as $presentation) {                            
                                        if (isset($presentation)) {
                                            $presentations[] = $presentation;
                                            echo "<li>" . $presentation->Title . "</li>";
                                        }
                                    }
                                    echo "</ul>";
                                    if ($count["NbPresentations"] == 1) {
                                        echo "Êtes-vous sûr de vouloir la supprimer avec cette catégorie&nbsp;?";
                                    }
                                    else {
                                        echo "Êtes-vous sûr de vouloir supprimer TOUTES ces présentations avec cette catégorie&nbsp;?";
                                    }
                                    // Si au moins une Presentation, mettre le tableau de Presentation à effacer en session pour ensuite aller au modal de confirmation de delete
                                    $_SESSION["deletePresentations"] = $presentations;
                                }
							}
						}
                        break;

                    case "deleteCategoryPresentations" :
						if (isset($_SESSION["deletePresentations"])) {
                            // Delete chaque Presentation
                            $presentationsModel = $this->getDAO("Presentations");
                            //var_dump($_SESSION["deletePresentations"]);
                            foreach ($_SESSION["deletePresentations"] as $presentation) {
                                //var_dump($presentation->Id);
                                $result = $presentationsModel->deletePresentation($presentation->Id);
                            }
                        }
                        //var_dump($_SESSION["deletePresentations"]);
                        if (isset($params["CategoryId"]) && $params["CategoryId"] != "") {
                            // Delete Category
                            $result = $categoriesModel->deleteCategory($params["CategoryId"]);
                        }
                        // Afficher la liste des Categories
                        $this->showCategories();
                        break;

					default :
						$this->showCategories();
                        break;
                        
			 	}
					
		  	}
		  	else {
		  		$this->showCategories();
		  	}

	  	} // end of switch


		private function showCategories() {
	  		$categoriesModel = $this->getDAO("Categories");
            $categories = $categoriesModel->getAllCategories();
            echo "<option value='0' selected disabled>Veuillez choisir une catégorie</option>";
			foreach ($categories as $category) {
                echo "<option value=" . $category->Id . ">" . $category->Description . "</option>";
            }
        }


	  	private function validateCategoryId($idCategory) {
			if (filter_var($idCategory, FILTER_SANITIZE_NUMBER_INT) !== false) {
				return false;
			}
			else {
				$errors .= "Une erreur s'est glissée lors de la modification, veuillez recommencer.<br>";
				return $errors;
			}
		}

    }
?>