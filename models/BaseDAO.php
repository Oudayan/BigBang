<?php
/**
 * @file     BaseDao.php
 * @author   Oudayan Dutta
 * @version  1.0
 * @date     31 janvier 2018
 * @brief    Modèle parent 
 * @details  Fonctions CRUD commumnes à toutes les classes et modèles
 */

	abstract class BaseDao {

		protected $db;

		public function __construct(PDO $dbPDO) {			
			$this->db = $dbPDO;
		}
			

		/**
		 * @brief      Deletes a row from a table
		 * @param      <STRING>  $primaryKey     The primary key
		 * @return     <object>  ( description_of_the_return_value )
		 */
		protected function delete($primaryKey) {
			$sql = "DELETE FROM " . $this->getTableName() . " WHERE " . $this->getPrimaryKey() ."=?";
			$data = array($primaryKey);
			return $this->query($sql, $data);
		}

		/**
		 * @brief      Reads the content from a table
		 * @param      VAR             $searchValue  The primary key from the
		 * @param      boolean|string  $primaryKey   The other column
		 * @return     <object>          ( description_of_the_return_value )
		 */
		protected function load($searchValue, $primaryKey = NULL) {
			if (!isset($primaryKey)) {
				$sql = "SELECT * FROM " . $this->getTableName() . " WHERE " . $this->getPrimaryKey() ."=?";
			}
			else {
				$sql = "SELECT * FROM " . $this->getTableName() . " WHERE " . $primaryKey ."=?";
			}
			$data = array($searchValue);
			return $this->query($sql, $data);
		}

		/**
		 * @brief      Reads all the rows from a table
		 * @return     <type>  ( description_of_the_return_value )
		 */
		protected function loadAll() {
			$sql = "SELECT * FROM " . $this->getTableName();
			return $this->query($sql);
		}

		/**
		 * @brief      Updates the value of a field in a table
		 * @param      VAR     $searchValue  The cle primaire from the
		 * @return     <type>  ( description_of_the_return_value )
		 */
		protected function UpdateField($id, $field, $value) {
            $sql = "UPDATE " . $this->getTableName() . " SET " . $field . "=? WHERE " . $this->getPrimaryKey() . "=?";
            $data = array($value, $id);
            return $this->query($sql, $data);
        }
       
		/**
		 * @brief      Makes a query to a table with the parameters you'll send
		 * @param      <STRING>   $sql  The query
		 * @param      <array>    $data   The values to insert into the query
		 * @return     <type>     ( description_of_the_return_value )
		 */
		final protected function query($sql, $data = array()) {
			try {
				$stmt = $this->db->prepare($sql);
				$stmt->execute($data);
			}
			catch (PDOException $e) {
				trigger_error("<p>La requête suivante a donné une erreur : $sql</p><p>Exception : " . $e->getMessage() . "</p>");
			}
			return $stmt;
		}
		
		/**
		 * @brief      Gets the name of the primary key of a table
		 * @return     <type>  ( description_of_the_return_value )
		 */
		final protected function getPrimaryKey() {
			//copyright salim
			$sql = "SHOW columns FROM " . $this->getTableName();
			$results = $this->query($sql);
			foreach ($results as $row) {
				if ($row["Key"]=="PRI") {
					return $row["Field"];
				}
			}
		}

		/**
		 * Gets the table name.
		 */
		abstract function getTableName();

	}

?>