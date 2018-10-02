<?php
/**
 * @file     PresentationsModel.php
 * @author   Oudayan Dutta
 * @version  1.0
 * @date     31 janvier 2018
 * @brief    Modèle des présentations 
 * @details  Toutes les fonctions CRUD pour les présentations
 */

	class PresentationsModel extends BaseDAO {

        // Nom de la table
		public function getTableName() {
			return "Presentations";
		}

        // Présentation par ID
		public function getPresentationById($id) {	
            $result = $this->load($id);
			$result->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Presentation'); 
			$presentation = $result->fetch();
			return $presentation;
		}
	
        // Toutes les présentations
		public function getAllPresentations() {
			$sql = "SELECT * FROM " . $this->getTableName() . " ORDER BY SortOrder ASC";
			$result = $this->query($sql);
			return $result->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Presentation");
		}

        // Nombre de présentation
		public function getPresentationsCount($categoryId) {
            $sql = "SELECT COUNT(" . $this->getPrimaryKey() . ") AS NbPresentations FROM " . $this->getTableName() . " WHERE CategoryId=?";
            $data = array($categoryId);
			return $this->query($sql, $data)->fetch();
    	}

        // Présentations par catégorie
        public function getPresentationsByCategory($value) {
			$sql = "SELECT * FROM " . $this->getTableName() . " WHERE CategoryId=? ";
			$data = array($value);
       		$result = $this->query($sql, $data);
       		$presentations = $result->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Presentation");
       		return $presentations;
		}

        // Sauvegarde d'une présentation
		public function savePresentation(Presentation $presentation) {
			if ($presentation->getId() && $this->load($presentation->getId())->fetch()) {
				//update
                $sql = "UPDATE " . $this->getTableName() . " SET Title=?, Description=?, CategoryId=?, SortOrder=?, StartDate=?, StartTime=?, EndTime=?, Location=?, Animator=?, Institution=? WHERE " . $this->getPrimaryKey() . "=?";
                $data = array($presentation->getTitle(), $presentation->getDescription(), $presentation->getCategoryId(), $presentation->getSortOrder(), $presentation->getStartDate(), $presentation->getStartTime(), $presentation->getEndTime(), $presentation->getLocation(), $presentation->getAnimator(), $presentation->getInstitution(), $presentation->getId());
                //die();
			}
			else {
				//insert 
                $sql = "INSERT INTO " . $this->getTableName() . "(Title, Description, CategoryId, SortOrder, StartDate, StartTime, EndTime, Location, Animator, Institution) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
                $data = array($presentation->getTitle(), $presentation->getDescription(), $presentation->getCategoryId(), $presentation->getSortOrder(), $presentation->getStartDate(), $presentation->getStartTime(), $presentation->getEndTime(), $presentation->getLocation(), $presentation->getAnimator(), $presentation->getInstitution());
			}
           	return $this->query($sql, $data);
		}

        // Effacer une présentation
        public function deletePresentation($id) {
            return $this->delete($id);
        }

	}
?>
