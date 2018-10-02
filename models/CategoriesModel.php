<?php
/**
 * @file     CategoriesModel.php
 * @author   Oudayan Dutta
 * @version  1.0
 * @date     31 janvier 2018
 * @brief    Modèle des catégories 
 * @details  Toutes les fonctions CRUD pour les catégories
 */
	class CategoriesModel extends BaseDAO {

        // Nom de la table
		public function getTableName() {
			return "Categories";
		}

        // Catégorie par ID
		public function getCategoryById($id) {
            $result = $this->load($id);
			$result->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Category'); 
			$category = $result->fetch();
			return $category;
		}
		
        // Toutes les catégories
		public function getAllCategories() {
			$sql = "SELECT * FROM " . $this->getTableName() . " ORDER BY SortOrder ASC";
			$result = $this->query($sql);
			return $result->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Category");
    	}

        // Nombre de catégories
		public function getCategoriesCount() {
            $sql = "SELECT COUNT(" . $this->getPrimaryKey() . ") AS NbCategories FROM " . $this->getTableName();
			return $this->query($sql)->fetch();
    	}

        //Sauvegarder une catégorie
		public function saveCategory(Category $category) {
			if ($category->getId() && $this->load($category->getId())->fetch()) {
				// update
				$sql = "UPDATE " . $this->getTableName() . " SET Description=? WHERE " . $this->getPrimaryKey() . "=?";
                $data = array($category->getDescription(), $category->getId());
			}
			else {
				// insert
                $sql = "INSERT INTO " . $this->getTableName() . "(Description, SortOrder) VALUES (?, ?)"; 
				$data = array($category->getDescription(), $category->getSortOrder());
			}
            $this->query($sql, $data);
           	return $this->db->lastInsertId();
		}

        // Effacer une catégories
        public function deleteCategory($id) {
        	//	------------------------- faire les verifications si on a des posts, avant de supprimer le sujet
            return $this->delete($id);
        }
        
    }

?>