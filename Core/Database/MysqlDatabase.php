<?php

	namespace Core\Database; // Un peu comme le repertoire du fichier actuel

	use \PDO; // On importe PDO

	/**
	 * Class MysqlDatabase
	 * @package Core\Database
	 */
	class MysqlDatabase extends Database{

		private $db_name;
		private $db_host;
		private $db_user;
		private $db_pass;
		private $pdo;

		// Constructeur
		public function __construct($db_name, $db_user = 'root', $db_pass = '', $db_host = 'localhost'){

			$this->db_name = $db_name;
			$this->db_user = $db_user;
			$this->db_pass = $db_pass;
			$this->db_host = $db_host;

		}

		/**
		 * on récupère PDO
		 */
		private function getPDO(){

			if($this->pdo === NULL){
				try {
					$pdo = new PDO('mysql:host=localhost;dbname=projetalain;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
				} catch (Exception $e) {
					die('Erreur : '.$e->getMessage());
				}
				$this->pdo = $pdo;
			}
			return $this->pdo;

		}

		/**
		 * exécute une requete simple
		 * @param $statement string (correspond à la requête à exécuter)
		 * @param $class_name string (le nom de la classe sur la quelle exécuter la requête)
		 * @param $one boolean (pour savoir si on veut récupérer un seul résultat ou plusieurs)
		 * @return $datas array (l'ensemble des résultats de la requête)
		 */
		public function query($statement, $class_name = null, $one = false){

			$req = $this->getPDO()->query($statement); // On récupère PDO
			// Si on ne veut pas récupérer des éléments
			if(
				strpos($statement, 'UPDATE') === 6 ||
				strpos($statement, 'INSERT') === 6 ||
				strpos($statement, 'DELETE') === 6
			){
				return $req;	
			}
			// sinon
			if($class_name === null){
				$req->setFetchMode(PDO::FETCH_OBJ);
			}else{
				$req->setFetchMode(PDO::FETCH_CLASS, $class_name);
			}
			if($one){
				$datas = $req->fetch();
			}else{
				$datas = $req->fetchAll();
			}

			return $datas;

		}

		/**
		 * exécute une requete préparée
		 * @param $statement string (correspond à la requête à exécuter)
		 * @param $class_name string (le nom de la classe sur la quelle exécuter la requête)
		 * @param $one boolean (pour savoir si on veut récupérer un seul résultat ou plusieurs)
		 * @return $datas array (l'ensemble des résultats de la requête)
		 */
		public function prepare($statement, $attributes, $class_name = null, $one = false){

			$req = $this->getPDO()->prepare($statement);
			$res = $req->execute($attributes);
			if(
				strpos($statement, 'UPDATE') === 6 ||
				strpos($statement, 'INSERT') === 6 ||
				strpos($statement, 'DELETE') === 6
			){
				return $res;
			}
			if($class_name === null){
				$req->setFetchMode(PDO::FETCH_OBJ);
			}else{
				$req->setFetchMode(PDO::FETCH_CLASS, $class_name);
			}

			if($one){
				$datas = $req->fetch();
			}else{
				$datas = $req->fetchAll();
			}

			return $datas;

		}

		/**
		 * Véifie si un élément est unique dans la table envoyée
		 * @param $statement string (correspond à la requête à exécuter)
		 * @return bollean (true si l'élément n'existe pas encore et false sinon)
		 */
		public function unicity($statement, $attributes){

			$req = $this->getPDO()->prepare($statement);
			$req->execute($attributes);
			return $req->fetchColumn();
		}

		public function number($statement, $attributes){

			$req = $this->getPDO()->prepare($statement);
			$req->execute($attributes);
			return $req->rowCount();
		}

		public function lastInsertId(){
			return $this->getPDO()->lastInsertId();
		}

	}

?>