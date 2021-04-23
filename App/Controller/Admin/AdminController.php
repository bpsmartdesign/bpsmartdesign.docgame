<?php

	namespace QueDuSal\Controller\Admin;


	class AdminController extends \QueDuSal\Controller\AppController {

		private function secureData($string) {

			if(ctype_digit($string)) {

				$string = intval($string);
			}else {

				$string = mysql_real_escape_string($string);
				$string = addcslashes($string, '%');
			}

			return $string;
		}

		private function postControl() {

			if(empty($_POST)) {

				header('Location: index');
				die();
			}
		}

		private function setTitle() {

			$p = stripcslashes(htmlentities($_GET['p']));
			$p = explode('.', $p);
			return 'Gestion DCN - '.ucfirst($p[3]).'s';
		}

		private function adminVerification() {

			if(!isset($_SESSION['aedjkAlain_2aKDAIkjeILAODkljeiLEKA4ç_H'])) {

				header('Location: forbidden');
				die();
			}
		}

		public function controlLog() {

			$username = $this->secureData(strtoupper($_POST['username'])); 
			$admin = $this->loadModel('user')->all();

			foreach($admin as $v) {

				if(strtoupper($v->username) == $username) {
					$_SESSION['id_lkjaldkafpoijdmfaAlain_2'] = $v->id;
				}
			}

			if(isset($_SESSION['id_lkjaldkafpoijdmfaAlain_2'])) {

				$actualUser = $this->loadModel('user')->find($_SESSION['id_lkjaldkafpoijdmfaAlain_2']);
				if(sha1(md5($this->secureData($_POST['password']))) == $actualUser->password) {
					$_SESSION['aedjkAlain_2aKDAIkjeILAODkljeiLEKA4ç_H'] = $username;
					$_SESSION['success'] = 'Welcome '.$username.' !';
					header('Location: index');
					die();
				}else {
					$error = 'pass';
					header('Location: '.$_SERVER['HTTP_REFERER']);
					die();
				}
			}else {
				$error .= 'user';
				header('Location: '.$_SERVER['HTTP_REFERER']);
				die();
			}
		}

		public function addElement() {

			$this->postControl();
			$this->adminVerification();
			
			$title = $this->setTitle();
			$table = $this->secureData($_POST['table']);
			$type = isset($_POST['type']) ? $this->secureData($_POST['type']) : null ;
			$form = new \Core\HTML\BootstrapForm;

			$this->render('admin.form.add'.ucfirst($table), compact('title', 'form', 'type'));
		}

		public function modElement() {

			$this->postControl();
			$this->adminVerification();
			
			$title = $this->setTitle();
			$table = $this->secureData($_POST['table']);
			$id = $this->secureData($_POST['id']);
			$form = new \Core\HTML\BootstrapForm;

			$old = $this->loadModel($table)->find($id);

			$this->render('admin.form.add'.ucfirst($table), compact('title', 'form', 'old'));
		}

		public function delElement() {

			$table = $this->secureData($_POST['table']);
			$id = $this->secureData($_POST['id']);
			$_SESSION['success'] = 'Element supprimé avec Succès !!!';

			if($table == 'adm_emetteur') {

				$this->loadModel('adm_emetteur')->delete($id);
				$this->loadModel('adm_recepteur')->delete($id);
			}else{

				$this->loadModel($table)->delete($id);
			}
			
			header('Location: '.$_SERVER['HTTP_REFERER']);
			die();
		}

		public function out() {

			$this->postControl();
			$this->adminVerification();

			$title = $this->setTitle();
			
			unset($_SESSION['aedjkAlain_2aKDAIkjeILAODkljeiLEKA4ç_H']);
			$this->renderLogOut('admin.home.forbidden', compact('title'));
		}

		public function forbidden() {

			$title = $this->setTitle();

			$this->renderForbidden('admin.home.forbidden', compact('title'));
		}

		public function nofound() {

			$title = $this->setTitle();

			$this->renderForbidden('admin.home.nofound', compact('title'));
		}

		public function __controlElement() {

			$this->postControl();
			$this->adminVerification();

			if(!isset($_POST['table'])) {

				die('Tu as fais comment pour arriver ici?');
			}else {

				$table = $this->secureData($_POST['table']);
				
				if($table == 'panne_adm') { // Maintenance

					$adm = $this->secureData($_POST['adm']);
					$panne = $this->secureData($_POST['panne']);
					$status = $this->secureData($_POST['status']);

					if(isset($_POST['modPanne_adm'])) {

						$id = $this->secureData($_POST['id']); 
						$this->loadModel($table)->update($id, [
							'id_adm' => $adm,
							'id_panne' => $panne,
							'status' => $status
							]);

						$_SESSION['success'] = 'La maintenance a été correctement modifié de la base de données';

						header('Location: maintenance');
						die();
					}else {

						$this->loadModel($table)->create([
							'id_adm' => $adm,
							'id_panne' => $panne,
							'status' => $status
							]);

						$_SESSION['success'] = 'La maintenance a été correctement ajouté à la base de données';

						header('Location: maintenance');
						die();
					}
				}elseif($table == 'planification') { // Planification

					$u2000_client = $this->secureData($_POST['u2000_client']);
					$subnet = $this->secureData($_POST['subnet']);
					$statut = $this->secureData($_POST['statut']);
					$gateway = $this->secureData($_POST['gateway']);
					$ip_address = $this->secureData($_POST['ip_address']);
					$subnet_mask = $this->secureData($_POST['subnet_mask']);

					if(isset($_POST['modPlanification'])) {

						$id = $this->secureData($_POST['id']); 
						$this->loadModel($table)->update($id, [
							'u2000_client' => $u2000_client,
							'subnet' => $subnet,
							'statut' => $statut,
							'gateway' => $gateway,
							'ip_address' => $ip_address,
							'subnet_mask' => $subnet_mask
							]);

						$_SESSION['success'] = 'La plannification a été correctement modifié de la base de données';

						header('Location: planification');
						die();
					}else {

						$this->loadModel($table)->create([
							'u2000_client' => $u2000_client,
							'subnet' => $subnet,
							'statut' => $statut,
							'gateway' => $gateway,
							'ip_address' => $ip_address,
							'subnet_mask' => $subnet_mask
							]);

						$_SESSION['success'] = 'La plannification a été correctement ajouté à la base de données';

						header('Location: planification');
						die();
					}
				}elseif($table == 'communique') { // ECC

					$adm_emetteur = $this->secureData($_POST['adm_emetteur']);
					$adm_recepteur = $this->secureData($_POST['adm_recepteur']);
					$distance = $this->secureData($_POST['distance']);
					$niveau = $this->secureData($_POST['niveau']);
					$mode = $this->secureData($_POST['mode']);
					$carte = $this->secureData($_POST['carte']);
					$port = $this->secureData($_POST['port']);
					$scc = $this->secureData($_POST['scc']);

					if(isset($_POST['modCommunique'])) {

						$id = $this->secureData($_POST['id']); 
						$this->loadModel($table)->update($id, [
							'id_adm_emetteur' => $adm_emetteur,
							'id_adm_recepteur' => $adm_recepteur,
							'distance' => $distance,
							'niveau' => $niveau,
							'mode' => $mode,
							'id_carte' => $carte,
							'id_port' => $port,
							'scc' => $scc,
							]);

						$_SESSION['success'] = 'L\'ECC a été correctement modifié de la base de données';

						header('Location: ecc');
						die();
					}else {

						$this->loadModel($table)->create([
							'id_adm_emetteur' => $adm_emetteur,
							'id_adm_recepteur' => $adm_recepteur,
							'distance' => $distance,
							'niveau' => $niveau,
							'mode' => $mode,
							'id_carte' => $carte,
							'id_port' => $port,
							'scc' => $scc,
							]);

						$_SESSION['success'] = 'L\'ECC a été correctement ajouté à la base de données';

						header('Location: ecc');
						die();
					}
				}elseif($table == 'canal') { // DCC

					$adm = $this->secureData($_POST['adm']);
					$port = $this->secureData($_POST['port']);
					$statut = $this->secureData($_POST['statut']);
					$channel = $this->secureData($_POST['channel']);
					$doc_ressources = $this->secureData($_POST['doc_ressources']);
					$statut_communication = $this->secureData($_POST['statut_communication']);
					$protocole = $this->secureData($_POST['protocole']);

					if(isset($_POST['modCanal'])) {

						$id = $this->secureData($_POST['id']); 
						$this->loadModel($table)->update($id, [
							'id_adm' => $adm,
							'id_port' => $port,
							'statut' => $statut,
							'channel' => $channel,
							'dcc_ressources' => $doc_ressources,
							'statut_communication' => $statut_communication,
							'id_protocole' => $protocole
							]);

						$_SESSION['success'] = 'Le canal de '.$adm.' - '.$port.' a été correctement modifié de la base de données';

						header('Location: dcc');
						die();
					}else {

						$this->loadModel($table)->create([
							'id_adm' => $adm,
							'id_port' => $port,
							'statut' => $statut,
							'channel' => $channel,
							'dcc_ressources' => $doc_ressources,
							'statut_communication' => $statut_communication,
							'id_protocole' => $protocole
							]);

						$_SESSION['success'] = 'Le canal de '.$adm.' - '.$port.' a été correctement ajouté à la base de données';

						header('Location: dcc');
						die();
					}
				}elseif($table == 'alarme_carte') { // Alarme

					$adm = $this->secureData($_POST['adm']);
					$carte = $this->secureData($_POST['carte']);
					$statut = $this->secureData($_POST['statut']);

					if(isset($_POST['modAlarme_carte'])) {

						$id = $this->secureData($_POST['id']); 
						$this->loadModel($table)->update($id, [
							'id_adm' => $adm,
							'id_carte' => $carte,
							'statut' => $statut
							]);

						$_SESSION['success'] = 'L\'alarme de '.$adm.' - '.$carte.' a été correctement modifié de la base de données';

						header('Location: alarme');
						die();
					}else {

						$this->loadModel($table)->create([
							'id_adm' => $adm,
							'id_carte' => $carte,
							'statut' => $statut
							]);

						$_SESSION['success'] = 'L\'alarme de '.$adm.' - '.$carte.' a été correctement ajouté à la base de données';

						header('Location: alarme');
						die();
					}
				}elseif($table == 'adm_emetteur') { // ADM

					$nom = $this->secureData($_POST['nom']);

					if(isset($_POST['ModAdm_emetteur'])) {

						$id = $this->secureData($_POST['id']); 
						$this->loadModel('adm_recepteur')->update($id, ['nom' => $nom]);
						$this->loadModel('adm_emetteur')->update($id, ['nom' => $nom]);

						$_SESSION['success'] = 'L\'ADM '.$nom.' a été correctement modifié de la base de données';

						header('Location: _adm');
						die();
					}else {

						$this->loadModel('adm_emetteur')->create(['nom' => $nom]);
						$this->loadModel('adm_recepteur')->create(['nom' => $nom]);

						$_SESSION['success'] = 'L\'ADM '.$nom.' a été correctement ajouté à la base de données';

						header('Location: _adm');
						die();
					}
				}elseif($table == 'equipement') { // ADM

					$nom = $this->secureData($_POST['nom']);

					if(isset($_POST['modEquipement'])) {

						$id = $this->secureData($_POST['id']); 
						$this->loadModel('equipement')->update($id, ['nom' => $nom]);

						$_SESSION['success'] = 'L\'équipement '.$nom.' a été correctement modifié de la base de données';

						header('Location: _equipement');
						die();
					}else {

						$this->loadModel('equipement')->create(['nom' => $nom]);

						$_SESSION['success'] = 'L\'équipement '.$nom.' a été correctement ajouté à la base de données';

						header('Location: _equipement');
						die();
					}
				}elseif($table == 'protocole') { // Protocole

					$nom = $this->secureData($_POST['nom']);

					if(isset($_POST['ModProtocole'])) {

						$id = $this->secureData($_POST['id']); 
						$this->loadModel('protocole')->update($id, ['nom' => $nom]);

						$_SESSION['success'] = 'Le protocole '.$nom.' a été correctement modifié de la base de données';

						header('Location: _protocole');
						die();
					}else {

						$this->loadModel('protocole')->create(['nom' => $nom]);

						$_SESSION['success'] = 'Le protocole '.$nom.' a été correctement ajouté à la base de données';

						header('Location: _protocole');
						die();
					}
				}elseif($table == 'carte') { // Carte

					$adm = $this->secureData($_POST['adm']);
					$slot = $this->secureData($_POST['slot']);
					$nom = $this->secureData($_POST['nom']);
					$type = $this->secureData($_POST['type']);

					if(isset($_POST['modCarte'])) {

						$id = $this->secureData($_POST['id']); 
						$this->loadModel('carte')->update($id, [
							'id_adm' => $adm,
							'id_slot' => $slot,
							'nom' => $nom,
							'type' => $type
						]);

						$_SESSION['success'] = 'La Carte a été correctement modifié de la base de données';

						header('Location: _carte');
						die();
					}else {

						$this->loadModel('carte')->create([
							'id_adm' => $adm,
							'id_slot' => $slot,
							'nom' => $nom,
							'type' => $type
						]);

						$_SESSION['success'] = 'La Carte a été correctement ajouté à la base de données';

						header('Location: _carte');
						die();
					}
				}elseif($table == 'port') { // Port

					$adm = $this->secureData($_POST['adm']);
					$carte = $this->secureData($_POST['carte']);
					$nom = $this->secureData($_POST['nom']);
					$statut = $this->secureData($_POST['statut']);

					if(isset($_POST['modPort'])) {

						$id = $this->secureData($_POST['id']); 
						$this->loadModel('port')->update($id, [
							'id_adm' => $adm,
							'id_carte' => $carte,
							'nom' => $nom,
							'statut' => $statut
						]);

						$_SESSION['success'] = 'Le port a été correctement modifié de la base de données';

						header('Location: _port');
						die();
					}else {

						$this->loadModel('port')->create([
							'id_adm' => $adm,
							'id_carte' => $carte,
							'nom' => $nom,
							'statut' => $statut
						]);

						$_SESSION['success'] = 'Le port a été correctement ajouté à la base de données';

						header('Location: _port');
						die();
					}
				}elseif($table == 'user') { // User

					$username = $this->secureData($_POST['username']);

					if(isset($_POST['modUser'])) {

						$id = $this->secureData($_POST['id']); 
						$this->loadModel('user')->update($id, ['username' => $username]);

						$_SESSION['success'] = 'L\'Utilisateur '.$username.' a été correctement modifié de la base de données';

						header('Location: _user');
						die();
					}else {

						$password = $this->secureData(sha1(md5($_POST['password'])));

						$this->loadModel('user')->create([
							'username' => $username,
							'password' => $password
						]);

						$_SESSION['success'] = 'L\'Utilisateur '.$username.' a été correctement ajouté à la base de données';

						header('Location: _user');
						die();
					}
				}elseif($table == 'panne_equipement') { // Maintenance

					$equipement = $this->secureData($_POST['equipement']);
					$panne = $this->secureData($_POST['panne']);
					$statut = $this->secureData($_POST['statut']);

					if(isset($_POST['modPanne_equipement'])) {

						$id = $this->secureData($_POST['id']); 
						$this->loadModel($table)->update($id, [
							'id_equipement' => $equipement,
							'id_panne' => $panne,
							'statut' => $statut
							]);

						$_SESSION['success'] = 'La maintenance a été correctement modifié de la base de données';

						header('Location: dwdm_maintenance');
						die();
					}else {

						$this->loadModel($table)->create([
							'id_equipement' => $equipement,
							'id_panne' => $panne,
							'statut' => $statut
							]);

						$_SESSION['success'] = 'La maintenance a été correctement ajouté à la base de données';

						header('Location: dwdm_maintenance');
						die();
					}
				}elseif($table == 'dwdm_planification') { // Maintenance

					$nom = $this->secureData($_POST['nom']);
					$capacite = $this->secureData($_POST['capacite']);
					$direction = $this->secureData($_POST['direction']);
					$source_ne = $this->secureData($_POST['source_ne']);
					$source_port = $this->secureData($_POST['source_port']);
					$sink_ne = $this->secureData($_POST['sink_ne']);
					$sink_port = $this->secureData($_POST['sink_port']);
					$fiber_cable = $this->secureData($_POST['fiber_cable']);
					$medium_cable = $this->secureData($_POST['medium_cable']);
					$creation_date = $this->secureData($_POST['creation_date']);
					$creator = $this->secureData($_POST['creator']);
					$maintener = $this->secureData($_POST['maintener']);

					if(isset($_POST['modDwdm_planification'])) {

						$id = $this->secureData($_POST['id']); 
						$this->loadModel($table)->update($id, [
							'nom' => $nom,
							'capacite' => $capacite,
							'direction' => $direction,
							'source_ne' => $source_ne,
							'source_port' => $source_port,
							'sink_ne' => $sink_ne,
							'sink_port' => $sink_port,
							'fiber_cable' => $fiber_cable,
							'medium_cable' => $medium_cable,
							'creation_date' => $creation_date,
							'creator' => $creator,
							'maintener' => $maintener
							]);

						$_SESSION['success'] = 'La Planification a été correctement modifié de la base de données';

						header('Location: dwdm_planification');
						die();
					}else {

						$this->loadModel($table)->create([
							'nom' => $nom,
							'capacite' => $capacite,
							'direction' => $direction,
							'source_ne' => $source_ne,
							'source_port' => $source_port,
							'sink_ne' => $sink_ne,
							'sink_port' => $sink_port,
							'fiber_cable' => $fiber_cable,
							'medium_cable' => $medium_cable,
							'creation_date' => $creation_date,
							'creator' => $creator,
							'maintener' => $maintener
							]);

						$_SESSION['success'] = 'La Planification a été correctement ajouté à la base de données';

						header('Location: dwdm_planification');
						die();
					}
				}elseif($table == 'fibre_optique') { // Maintenance

					$adm = $this->secureData($_POST['adm']);
					$carte = $this->secureData($_POST['carte']);
					$slot = $this->secureData($_POST['slot']);
					$port = $this->secureData($_POST['port']);
					$input_power = $this->secureData($_POST['input_power']);
					$ref_input_power = $this->secureData($_POST['ref_input_power']);
					$date = $this->secureData($_POST['date']);
					$etat = $this->secureData($_POST['etat']);
					$output_power = $this->secureData($_POST['output_power']);
					$ref_output_power = $this->secureData($_POST['ref_output_power']);
					$dateoutput = $this->secureData($_POST['dateoutput']);
					$etatoutput = $this->secureData($_POST['etatoutput']);

					if(isset($_POST['modEtat'])) {

						$id = $this->secureData($_POST['id']); 
						$this->loadModel($table)->update($id, [
							'id_adm' => $adm,
							'id_carte' => $carte,
							'id_slot' => $slot,
							'id_port' => $port,
							'input_power' => $input_power,
							'ref_input_power' => $ref_input_power,
							'date' => $date,
							'etat' => $etat,
							'output_power' => $output_power,
							'ref_output_power' => $ref_output_power,
							'dateouput' => $dateoutput,
							'etatouput' => $etatoutput
							]);

						$_SESSION['success'] = 'L\'état de communication a été correctement modifié de la base de données';

						header('Location: etat');
						die();
					}else {

						$this->loadModel($table)->create([
							'id_adm' => $adm,
							'id_carte' => $carte,
							'id_slot' => $slot,
							'id_port' => $port,
							'input_power' => $input_power,
							'ref_input_power' => $ref_input_power,
							'date' => $date,
							'etat' => $etat,
							'output_power' => $output_power,
							'ref_output_power' => $ref_output_power,
							'dateouput' => $dateoutput,
							'etatouput' => $etatoutput
							]);

						$_SESSION['success'] = 'L\'état de communication a été correctement ajouté à la base de données';

						header('Location: etat');
						die();
					}
				}elseif($table == 'config') { // Maintenance

					$ip_serveur = $this->secureData($_POST['ip_serveur']);
					$nbr_adm = $this->secureData($_POST['nbr_adm']);
					$adm_gne = $this->secureData($_POST['adm_gne']);
					$ip_gne = $this->secureData($_POST['ip_gne']);
					$statut_gne = $this->secureData($_POST['statut_gne']);
					$protocole_gne = $this->secureData($_POST['protocole_gne']);
					
					$adm_ne ='';
					$ip_ne ='';
					$canal ='';

					for($i = 0; $i < $nbr_adm-2; $i++) {
						$adm_ne .= $this->secureData($_POST['adm_ne'.$i]).' -- ';
						$ip_ne .= $this->secureData($_POST['ip_ne'.$i]).' -- ';
						$canal .= $this->secureData($_POST['canal'.$i]).' -- ';
					}

					$adm_ne .= $this->secureData($_POST['adm_ne'.($nbr_adm-2)]);
					$ip_ne .= $this->secureData($_POST['ip_ne'.($nbr_adm-2)]);
					$canal .= $this->secureData($_POST['canal'.($nbr_adm-2)]);

					if(isset($_POST['modConfig'])) {

						$id = $this->secureData($_POST['id']); 
						$this->loadModel($table)->update($id, [
							'ip_serveur' => $ip_serveur,
							'nbr_adm' => $nbr_adm,
							'id_adm_gne' => $adm_gne,
							'ip_gne' => $ip_gne,
							'statut_gne' => $statut_gne,
							'id_protocole_gne' => $protocole_gne,
							'id_adm_ne' => $adm_ne,
							'ip_ne' => $ip_ne,
							'canal' => $canal,
							]);

						$_SESSION['success'] = 'La configuration a été correctement modifié de la base de données';

						header('Location: __tableau');
						die();
					}else {

						$this->loadModel($table)->create([
							'ip_serveur' => $ip_serveur,
							'nbr_adm' => $nbr_adm,
							'id_adm_gne' => $adm_gne,
							'ip_gne' => $ip_gne,
							'statut_gne' => $statut_gne,
							'id_protocole_gne' => $protocole_gne,
							'id_adm_ne' => $adm_ne,
							'ip_ne' => $ip_ne,
							'canal' => $canal,
							]);

						$_SESSION['success'] = 'La configuration a été correctement ajouté à la base de données';

						header('Location: __tableau');
						die();
					}
				}
			}
		}

		public function index() {
			
			$this->adminVerification();

			$title = 'Gestionnaire DCN - Dashboard';
			$today = date('Y/m/d');

			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;

			$_SESSION['success'] = null;

			$this->render('admin.home.index', compact('title', 'success'));
		}

		public function sdh() {
			
			$this->adminVerification();

			$title = 'Gestionnaire DCN - SDH';
			$today = date('Y/m/d');

			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;

			$_SESSION['success'] = null;

			$this->render('admin.sdh.sdh', compact('title', 'success'));
		}

		public function dwdm() {
			
			$this->adminVerification();

			$title = 'Gestionnaire DCN - DWDM';
			$today = date('Y/m/d');

			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;

			$_SESSION['success'] = null;

			$this->render('admin.dwdm.dwdm', compact('title', 'success'));
		}

		public function maintenance() {
			
			$this->adminVerification();

			$title = $this->setTitle();
			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
			$_SESSION['success'] = null;

			$panne_adms = $this->loadModel('panne_adm')->all();

			$this->render('admin.home.maintenance', compact('title', 'success', 'panne_adms'));
		}

		public function planification() {
			
			$this->adminVerification();

			$title = $this->setTitle();
			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
			$_SESSION['success'] = null;

			$planifications = $this->loadModel('planification')->all();

			$this->render('admin.home.planification', compact('title', 'success', 'planifications'));
		}

		public function dwdm_maintenance() {
			
			$this->adminVerification();

			$title = $this->setTitle();
			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
			$_SESSION['success'] = null;

			$panne_equipements = $this->loadModel('panne_equipement')->all();

			$this->render('admin.dwdm.dwdm_maintenance', compact('title', 'success', 'panne_equipements'));
		}

		public function dwdm_planification() {
			
			$this->adminVerification();

			$title = $this->setTitle();
			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
			$_SESSION['success'] = null;

			$dwdm_planifications = $this->loadModel('dwdm_planification')->all();

			$this->render('admin.dwdm.dwdm_planification', compact('title', 'success', 'dwdm_planifications'));
		}

		public function ecc() {
			
			$this->adminVerification();

			$title = $this->setTitle();
			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
			$_SESSION['success'] = null;

			$eccs = $this->loadModel('communique')->all();

			$this->render('admin.sdh.ecc', compact('title', 'success', 'eccs'));
		}

		public function dcc() {
			
			$this->adminVerification();

			$title = $this->setTitle();
			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
			$_SESSION['success'] = null;
			$form = new \Core\HTML\BootstrapForm;

			$dccs = $this->loadModel('canal')->all();
			$adms = $this->loadModel('adm_recepteur')->all();

			$this->render('admin.sdh.dcc', compact('title', 'success', 'form', 'dccs', 'adms'));
		}

		public function alarme() {
			
			$this->adminVerification();

			$title = $this->setTitle();
			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
			$_SESSION['success'] = null;
			$form = new \Core\HTML\BootstrapForm;

			$adms = $this->loadModel('adm_recepteur')->all();
			$alarmes = $this->loadModel('alarme_carte')->all();

			$this->render('admin.sdh.alarme', compact('title', 'success', 'form', 'alarmes', 'adms'));
		}

		public function etat() {
			
			$this->adminVerification();

			$title = $this->setTitle();
			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
			$_SESSION['success'] = null;
			$form = new \Core\HTML\BootstrapForm;

			$etats = $this->loadModel('fibre_optique')->all();

			$this->render('admin.sdh.etat', compact('title', 'success', 'form', 'etats'));
		}

		public function __graphique() {
			
			$this->adminVerification();

			$title = $this->setTitle();
			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
			$_SESSION['success'] = null;

			$configs = $this->loadModel('config')->all();
			$this->render('admin.config.__graphique', compact('title', 'success', 'configs'));
		}

		public function __tableau() {
			
			$this->adminVerification();

			$title = $this->setTitle();
			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
			$_SESSION['success'] = null;

			$configs = $this->loadModel('config')->all();
			$this->render('admin.config.__tableau', compact('title', 'success', 'configs'));
		}

		public function __addEnreg() {
			
			$this->adminVerification();

			$title = $this->setTitle();
			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
			$_SESSION['success'] = null;
			$form = new \Core\HTML\BootstrapForm;

			$this->render('admin.config.__addEnreg', compact('title', 'success', 'form'));
		}

		public function _adm() {
			
			$this->adminVerification();

			$title = $this->setTitle();
			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
			$_SESSION['success'] = null;

			$adms = $this->loadModel('adm_emetteur')->all();
			$this->render('admin.administration._adm', compact('title', 'success', 'adms'));
		}

		public function _equipement() {
			
			$this->adminVerification();

			$title = $this->setTitle();
			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
			$_SESSION['success'] = null;

			$equipements = $this->loadModel('equipement')->all();
			$this->render('admin.administration._equipement', compact('title', 'success', 'equipements'));
		}

		public function _carte() {
			
			$this->adminVerification();

			$title = $this->setTitle();
			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
			$_SESSION['success'] = null;

			$cartes = $this->loadModel('carte')->all();
			$this->render('admin.administration._carte', compact('title', 'success', 'cartes'));
		}

		public function _panne() {
			
			$this->adminVerification();

			$title = $this->setTitle();
			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
			$_SESSION['success'] = null;

			$pannes = $this->loadModel('panne')->all();
			$this->render('admin.administration._panne', compact('title', 'success', 'pannes'));
		}

		public function _port() {
			
			$this->adminVerification();

			$title = $this->setTitle();
			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
			$_SESSION['success'] = null;

			$ports = $this->loadModel('port')->all();
			$this->render('admin.administration._port', compact('title', 'success', 'ports'));
		}

		public function _protocole() {
			
			$this->adminVerification();

			$title = $this->setTitle();
			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
			$_SESSION['success'] = null;

			$protocoles = $this->loadModel('protocole')->all();
			$this->render('admin.administration._protocole', compact('title', 'success', 'protocoles'));
		}

		public function _user() {
			
			$this->adminVerification();

			$title = $this->setTitle();
			$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
			$_SESSION['success'] = null;

			$users = $this->loadModel('user')->all();
			$this->render('admin.administration._user', compact('title', 'success', 'users'));
		}
	}
 ?>