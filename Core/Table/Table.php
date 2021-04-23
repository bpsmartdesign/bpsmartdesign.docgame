<?php 

	namespace Core\Table; // Un peu comme le repertoire du fichier actuel

	use Core\Database\Database; // On importe Database

	/**
	 * Class Table
	 * @package Core\Table
	 */
	class Table{
		
		protected $table;
		protected $db;

		// Constructeur
		public function __construct(Database $db){
			$this->db = $db;
			if(is_null($this->table)){
				$parts = explode('\\', get_class($this));
				$class_name = end($parts);
				$this->table = strtolower(str_replace('Table', '', $class_name));
			}
		}

		/**
		 * recupère tous les éléments présents dans la table en cours
		 */
		public function all(){
			return $this->query('	SELECT * 
								 						FROM '.$this->table);
		}

		/**
		 * recupère un élément dans la table en cours e fonction de l'id en paramètre
		 */
		public function find($id){
			
			return $this->query("
				SELECT *
				FROM ".$this->table."
				WHERE id=?
			",[$id], true);

		}

		public function nbr(){

			return $this->query("
				SELECT COUNT(*) AS nbr
				FROM ".$this->table."
				");
		}

		public function already_exist($elt, $elt_cible){

			return $this->db->unicity("
				SELECT COUNT(*)
				FROM ".$this->table."
				WHERE ".$elt_cible." = ?
				",[$elt]);
		}

		public function nbr_elt($elt, $elt_cible){

			return $this->db->number("
				SELECT COUNT(*)
				FROM ".$this->table."
				WHERE ".$elt_cible." = ?
				",[$elt]);
		}

		/**
		 * modifie les champs ($fields) en paramèter de la table en cours en fonction de l'id
		 * @param $id int 
		 * @param $fields array 
		 */
		public function update($id, $fields){

			$sql_parts = [];
			$attributs = [];
			foreach ($fields as $k => $v){

				$sql_parts[] = "$k = ?";
				$attributs[] = $v;

			}
			$attributs[] = $id;
			$sql_part = implode(', ', $sql_parts);
			
			return $this->query("
				UPDATE {$this->table}
				SET $sql_part
				WHERE id = ?
			",$attributs, true);

		}

		public function lastId(){
			return $this->db->lastInsertId();
		}

		/**
		 * Supprime un element de la table en fonction de l'id en paramètre
		 */
		public function delete($id){

			return $this->query("
				DELETE FROM {$this->table}
				WHERE id = ?
			",[$id], true);
		}

		public function vider($id) {

			return $this->query("
				DELETE FROM {$this->table}
				WHERE id > ?
			",[$id]);
		}

		/**
		 * ajout une nouvelle ligne dans la table en cours
		 * @param $fields array 
		 */
		public function create($fields){

			$sql_parts = [];
			$attributs = [];
			foreach ($fields as $k => $v){

				$sql_parts[] = "$k = ?";
				$attributs[] = $v;

			}
			$sql_part = implode(', ', $sql_parts);
			
				// var_dump($attributs);
				// die();
			return $this->query("
				INSERT INTO {$this->table}
				SET $sql_part
			",$attributs, true);

		}

		/**
		 * Récupère les éléments et les renvoi sous forme de tableau
		 * @param $key int
		 * @param $value string 
		 */
		public function extract($key, $value){
			$records = $this->all();
			$return = [];
			foreach($records as $v){
				$return[$v->$key] = $v->$value;
			}
			return $return;
		}

		public function query($statement, $attributs = null, $one = false){

			if($attributs){
				return $this->db->prepare($statement, $attributs, str_replace('Table', 'Entity', get_class($this)), $one);
			}else{
				return $this->db->query($statement, str_replace('Table', 'Entity', get_class($this)), $one);
			}
		} 

	}
?>