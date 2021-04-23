<?php

	namespace Core\Auth;// Un peu comme le repertoire du fichier actuel

	use Core\Database\Database; // On importe Database

	/**
	 * Class DBAuth
	 * @package Core\DBAuth
	 */
	class DBAuth{

		private $db;

		public function __construct(Database $db){
			$this->db = $db;
		}

		public function getUserId(){
			if($this->logged){
				return $_SESSION['auth'];
			}
			return false;
		}

		/**
		 * @param $username
		 * @param $password
		 * @return boolean
		 */
		public function login($username, $password){
			$user = $this->db->prepare('SELECT * FROM users WHERE pseudo = ?', [$username], null, true);
			if($user){
				if($user->mdp === sha1($password)){
					$_SESSION['auth'] = $user->id;
					setcookie('pseudo', $user->pseudo, time() + 3*24*3600);
					setcookie('pp', $user->pp, time() + 3*24*3600);

					return true;
				}
			}else{
				return false;
			}
		}

		public function logged($level){
			if(isset($_SESSION['auth'])){
				if($_SESSION['level'] == $level){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
			return isset($_SESSION['auth']);
		}

		public function unlogged(){
			if(isset($_SESSION['auth'])){

				unset($_SESSION);
			}
		}
	}

?>