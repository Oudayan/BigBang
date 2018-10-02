<?php
/**
 * @file     PresentationsController.php
 * @author   Oudayan Dutta
 * @version  1.0
 * @date     31 janvier 2018
 * @brief    Controlleur pour les présentations
 * @details  
 */

	class PresentationsController extends BaseController {

		public function process(array $params) {
			
            // Chercher le modèle des Presentations
            $presentationsModel = $this->getDAO("Presentations");

            // Si le paramètre action existe
			if (isset($params["action"])) {
				
                // Switch en fonction de l'action qui nous est envoyée
				switch ($params["action"]) {
					
                    // Affichage des présentations
					case "displayPresentations" :
                        $this->showPresentations();
                        break;

                    // Affichage du select de l'ordre des présentations
					case "displaySortOrder" :
                        if (isset($params["PresentationId"]) && isset($params["CategoryId"])) {
                            $presentations = $presentationsModel->getPresentationsByCategory($params["CategoryId"]);
                            $cnt = 1;
                            echo "<option value='0' selected disabled>Veuillez choisir un ordre d'affichage</value>";
                            foreach ($presentations as $presentation) {
                                echo "<option value=" . $presentation->SortOrder . ">" . $presentation->SortOrder . "</option>";
                                $cnt++;
                            }
                            // Si nouvelle présentation ou autre catégorie que l'originale, ajouter un item au menu
                            $currentPresentation = $presentationsModel->getPresentationById($params["PresentationId"]);
                            if ($params["PresentationId"] == 0 || ($params["CategoryId"] != $currentPresentation->CategoryId)) {
                                echo "<option value=" . $cnt . ">" . $cnt . "</value>";
                            }
                        }
                        break;

                    /* Mise à jour de l'ordre de chaque présentation dans une catégorie
					case "updateSortOrder" :
                        if (isset($params["CategoryId"]) && isset($params["SortOrder"])) {
                            $presentations = $presentationsModel->getPresentationsByCategory($params["CategoryId"]);
                            foreach ($presentations as $presentation) {
                                if ($presentation->SortOrder > $params["SortOrder"]) {
                                    $presentationsModel->UpdateField($presentation->Id, "SortOrder", $presentation->SortOrder++);
                                }
                            }
                            $this->showPresentations();
                        }
                        else {
                            $errors = "<p>Une erreur s'est produite lors de l'insertion des informations</p><p>Veuillez recommencer</p>";
                            $this->showPresentations($errors);
                        } 
                        break;
                    */

                    // Sauvegarde et modification d'une présentation
                    case "savePresentation" :
                        if (isset($params["PresentationId"]) && isset($params["Title"]) && isset($params["Description"]) && isset($params["CategoryId"]) && isset($params["StartDate"]) && isset($params["StartTime"]) && isset($params["EndTime"]) && isset($params["Location"]) && isset($params["Animator"]) && isset($params["Institution"])) {
                            $errors = "";
                            // Validation
                            if (trim($params["PresentationId"]) == "") {
                                $id = 0;
                            }
                            else {
                                $id = $params["PresentationId"]; 
                            }
                            if (!isset($params["SortOrder"]) || trim($params["SortOrder"]) == "") {
                                $NbOrder = $presentationsModel->getPresentationsCount($params["CategoryId"]);
                                $SortOrder = ++$NbOrder["NbPresentations"];
                            }
                            else {
                                $SortOrder = $params["SortOrder"];
                            }
                            if (trim($params["Title"]) != "" && trim($params["Description"]) != "" && trim($params["CategoryId"]) != "" && trim($params["StartDate"]) != "" && trim($params["StartTime"]) != "" && trim($params["EndTime"]) != "" && trim($params["Location"]) != "" && trim($params["Animator"]) != "" && trim($params["Institution"]) != "") {
                                 // Instanciation de l'objet Presentation a sauvegarder ou modifier
                                $newPresentation = new Presentation($id, $params["Title"], $params["Description"], $params["CategoryId"], $SortOrder, $params["StartDate"], $params["StartTime"], $params["EndTime"], $params["Location"], $params["Animator"], $params["Institution"]);
                                $result = $presentationsModel->savePresentation($newPresentation);
                                if ($result) {
                                    $this->showPresentations();
                                }
                                else {
                                    $errors = "<p>Une erreur s'est produite lors de l'insertion des informations</p><p>Veuillez recommencer</p>";
                                    $this->showPresentations($errors);
                                }	
                            }	
                            else {
                                $errors = "<p>Une erreur s'est produite lors de l'insertion des informations</p><p>Veuillez recommencer</p>";
                                $this->showPresentations($errors);
                            }	
                        }
                        else {	
                            $errors = "<p>Une erreur s'est produite lors de l'insertion des informations</p><p>Veuillez recommencer</p>";
                            $this->showPresentations($errors);
                        }	
                        break;

                    // Ajout des données d'une présentation au formulaire d'ajout/modification de présentation
                    case "updatePresentation" :
						if (isset($params["PresentationId"])) {
							$errors = $this->validateId($params["PresentationId"]);
							if (!$errors) {
								$presentation = $presentationsModel->getPresentationById($params["PresentationId"]);
                                echo json_encode($presentation);
							}
                            else {
                                $errors = "<p>Une erreur s'est produite lors de la requête</p><p>Veuillez recommencer</p>";
                                $this->showPresentations($errors);
                            }
						}
                        break;

                    // Effacer une présentation
                    case "deletePresentation" :
						if (isset($params["PresentationId"])) {
							$errors = $this->validateId($params["PresentationId"]);
							if (!$errors) {
								$result = $presentationsModel->deletePresentation($params["PresentationId"]);
                                $this->showPresentations();
							}
                            else {
                                $errors = "<p>Une erreur s'est produite lors de la requête</p><p>Veuillez recommencer</p>";
                                $this->showPresentations($errors);
                            }
						}
                        else {
                            $errors = "<p>Une erreur s'est produite lors de la requête</p><p>Veuillez recommencer</p>";
                            $this->showPresentations($errors);
                        }
						break;

                    // Action par défaut
					default :
        				$this->showPresentations();
			 	}
					
		  	}
		  	else {
		  		$this->displayView("DisplayPresentations");
		  	}

	  	}

        // Afficher les présentations par catégories
   		public function showPresentations($errors = "") {
            // Chercher le modèle des Categories
            $categoriesModel = $this->getDAO("Categories");
            // Chercher le modèle des Presentations
            $presentationsModel = $this->getDAO("Presentations");
            if ($errors != "") {
                echo '<div id="error" class="text-center text-danger">' . $errors . '</div>';
            }
            echo '<div class="section_accordion my-3">';
			$categories = $categoriesModel->getAllCategories();
			$presentations = $presentationsModel->getAllPresentations();
            foreach ($categories as $category) {
                echo    '<section class="section_sortable">';
                echo        '<h3 class="section_title mt-3 px-5 py-1">' . $category->Description . '</h3>';
                echo        '<div class="article_accordion mb-2">';
                foreach ($presentations as $presentation) {
                    if ($category->Id == $presentation->CategoryId) {
                        echo    '<article class="article_sortable ml-3 mb-2">';
                        echo        '<h4 class="article_title mt-3 px-5 py-1">' . $presentation->Title . '</h4>';
                        echo        '<div class="article_content px-5 py-3">';
                        echo            '<h5>Résumé&nbsp;:</h5>';
                        echo            '<p>' . $presentation->Description . '</p>';
                        echo            '<div class="row py-2">';
                        echo                '<div class="col-lg-6">';
                        echo                    '<div class="row">';
                        echo                        '<div class="col-sm-2">';
                        echo                            '<h5>Date&nbsp;:</h5>';
                        echo                        '</div>';
                        echo                        '<div class="col-sm-4">';
                        echo                            $presentation->StartDate;
                        echo                        '</div>';
                        echo                        '<div class="col-sm-2">';
                        echo                            '<h5>Heure&nbsp;:</h5>';
                        echo                        '</div>';
                        echo                        '<div class="col-sm-4">';
                        echo                            $presentation->StartTime . " à ". $presentation->EndTime;
                        echo                        '</div>';
                        echo                    '</div>';
                        echo                '</div>';
                        echo                '<div class="col-lg-6">';
                        echo                    '<div class="row py-2">';
                        echo                        '<div class="col-sm-4">';
                        echo                            '<h5>Salle&nbsp;:</h5>';
                        echo                        '</div>';
                        echo                        '<div class="col-sm-8">';
                        echo                            $presentation->Location;
                        echo                        '</div>';
                        echo                    '</div>';
                        echo                '</div>';
                        echo            '</div>';
                        echo            '<div class="row py-2">';
                        echo                '<div class="col-lg-6">';
                        echo                    '<div class="row">';
                        echo                        '<div class="col-sm-4">';
                        echo                            '<h5>Présentateur&nbsp;:</h5>';
                        echo                        '</div>';
                        echo                        '<div class="col-sm-8">';
                        echo                            $presentation->Animator;
                        echo                        '</div>';
                        echo                    '</div>';
                        echo                '</div>';
                        echo                '<div class="col-lg-6">';
                        echo                    '<div class="row">';
                        echo                        '<div class="col-sm-4">';
                        echo                            '<h5>Institution&nbsp;:</h5>';
                        echo                        '</div>';
                        echo                        '<div class="col-sm-8">';
                        echo                            $presentation->Institution;
                        echo                        '</div>';
                        echo                    '</div>';
                        echo                '</div>';
                        echo            '</div>';
                        echo            '<div class="d-flex justify-content-around py-2">';
                        echo                '<button type="button" class="btn btn-mod" onclick="updatePresentation(' . $presentation->Id . ')">Modifier</button>';
                        echo                '<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal' . $presentation->Id . '">Supprimer</button>';
                        echo                '<div class="modal" id="deleteModal' . $presentation->Id . '" tabindex="-1" role="dialog" aria-labelledby="deleteModal' . $presentation->Id . '" aria-hidden="true">';
                        echo                    '<div class="modal-dialog modal-dialog-centered" role="document">';
                        echo                        '<div class="modal-content">';
                        echo                            '<div class="modal-header">';
                        echo                                '<h5 class="modal-title" id="deleteModal' . $presentation->Id . '">Supprimer présentation</h5>';
                        echo                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        echo                            '</div>';
                        echo                            '<div class="modal-body">';
                        echo                                '<p>Êtes-vous sûr de vouloir supprimer cette présentation&nbsp;?</p>';
                        echo                            '</div>';
                        echo                            '<div class="modal-footer">';
                        echo                                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>';
                        echo                                '<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deletePresentation(' . $presentation->Id . ')">Supprimer</button>';
                        echo                            '</div>';
                        echo                        '</div>';
                        echo                    '</div>';
                        echo                '</div>';
                        echo            '</div>';
                        echo        '</div>';
                        echo    '</article>';
                    }
                }
                echo        '</div>';
                echo    '</section>';
            }
            echo '</div>';
	  	}

        // Validation d'un Id
  		private function validateId($id) {
			if (filter_var($id, FILTER_SANITIZE_NUMBER_INT) !== false) {
				return false;
			}
			else {
				$errors .= "Impossible d'afficher ce sujet, veuillez recommencer.<br>";
				return $errors;
			}	
		}

	}

?>