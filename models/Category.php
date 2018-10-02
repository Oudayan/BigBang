<?php
/**
 * @file     Category.php
 * @author   Oudayan Dutta
 * @version  1.0
 * @date     31 janvier 2018
 * @brief    Classe Category 
 * @details  Toutes les fonctions getter et setters pour Category
 */

    class Category {
        
		private $id;
        private $description;
		private $sortOrder;

		public function __construct($id = 0, $description = "", $sortOrder = "") {
			$this->setId($id);
			$this->setDescription($description);
			$this->setSortOrder($sortOrder);
		}

    // GETTERS
        // Getter pour l'id
        public function getId() {
            return $this->id;
        }
        // Getter pour la description
        public function getDescription() {
            return $this->description;
        }
        // Getter pour le sortOrder
        public function getSortOrder() {
            return $this->sortOrder;
        }

    // SETTERS
        // Setter pour l'id
        public function setId($id){
            if (is_numeric($id) && trim($id)!="") {
                $this->id = $id;
            }
        }
        // Setter pour la description
        public function setDescription($description){
            if (is_string($description) && trim($description)!="") {
                $this->description = $description;
            }
        }    
        // Setter pour le sortOrder
        public function setSortOrder($sortOrder){
            if (is_numeric($sortOrder) && trim($sortOrder)!="") {
                $this->sortOrder = $sortOrder;
            }
        }

    }

?>
