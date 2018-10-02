<?php
/**
 * @file     Presentation.php
 * @author   Oudayan Dutta
 * @version  1.0
 * @date     31 janvier 2018
 * @brief    Classe Presentation 
 * @details  Toutes les fonctions getter et setters pour Presentation
 */
    class Presentation {
        
		private $id;
		private $title;
        private $description;
		private $categoryId;
		private $sortOrder;
		private $startDate;
		private $startTime;
		private $endTime;
		private $location;
		private $animator;
		private $institution;

		public function __construct($id = 0, $title = "", $description = "", $categoryId = 0, $sortOrder = 0, $startDate = "", $startTime = "", $endTime = "", $location = "", $animator = "", $institution = "") {
			$this->setId($id);
			$this->setTitle($title);
			$this->setDescription($description);
			$this->setCategoryId($categoryId);
			$this->setSortOrder($sortOrder);
			$this->setStartDate($startDate);
			$this->setStartTime($startTime);
			$this->setEndTime($endTime);
			$this->setLocation($location);
			$this->setAnimator($animator);
			$this->setInstitution($institution);
		}

    // GETTERS
        // Getter pour id
        public function getId() {
            return $this->id;
        }
        // Getter pour title
        public function getTitle() {
            return $this->title;
        }
        // Getter pour description
        public function getDescription() {
            return $this->description;
        }
        // Getter pour categoryId
        public function getCategoryId() {
            return $this->categoryId;
        }
        // Getter pour sortOrder
        public function getSortOrder() {
            return $this->sortOrder;
        }
        // Getter pour startDate
        public function getStartDate() {
            return $this->startDate;
        }
        // Getter pour startTime
        public function getStartTime() {
            return $this->startTime;
        }
        // Getter pour endTime
        public function getEndTime() {
            return $this->endTime;
        }
        // Getter pour location
        public function getLocation() {
            return $this->location;
        }
        // Getter pour animator
        public function getAnimator() {
            return $this->animator;
        }
        // Getter pour institution
        public function getInstitution() {
            return $this->institution;
        }

    // SETTERS
        // Setter pour id
        public function setId($id){
            if (is_numeric($id) && trim($id)!="") {
                $this->id = $id;
            }
        }
        // Setter pour title
        public function setTitle($title){
            if (is_string($title) && trim($title)!="") {
                $this->title = $title;
            }
        }    
        // Setter pour description
        public function setDescription($description){
            if (is_string($description) && trim($description)!="") {
                $this->description = $description;
            }
        }    
        // Setter pour categoryId
        public function setCategoryId($categoryId){
            if (is_numeric($categoryId) && trim($categoryId)!="") {
                $this->categoryId = $categoryId;
            }
        }
        // Setter pour sortOrder
        public function setSortOrder($sortOrder){
            if (is_numeric($sortOrder) && trim($sortOrder)!="") {
                $this->sortOrder = $sortOrder;
            }
        }
        // Setter pour startDate
        public function setStartDate($startDate){
            if (is_string($startDate) && trim($startDate)!="") {
                $this->startDate = $startDate;
            }
        }    
        // Setter pour startTime
        public function setStartTime($startTime){
            if (is_string($startTime) && trim($startTime)!="") {
                $this->startTime = $startTime;
            }
        }    
        // Setter pour endTime
        public function setEndTime($endTime){
            if (is_string($endTime) && trim($endTime)!="") {
                $this->endTime = $endTime;
            }
        }    
        // Setter pour location
        public function setLocation($location){
            if (is_string($location) && trim($location)!="") {
                $this->location = $location;
            }
        }    
        // Setter pour animator
        public function setAnimator($animator){
            if (is_string($animator) && trim($animator)!="") {
                $this->animator = $animator;
            }
        }    
        // Setter pour institution
        public function setInstitution($institution){
            if (is_string($institution) && trim($institution)!="") {
                $this->institution = $institution;
            }
        }    

    }

?>
