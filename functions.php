<?php 
	
	require_once("../config_global.php");
	$database = "if15_mikkmae";
	
	
	//tekitatakse sessioon, mida hoitakse serveris,
	// k�ik session muutujad on k�ttesaadavad kuni viimase brauseriakna sulgemiseni
	session_start();
	
	
	// v�tab andmed ja sisestab ab'i
	// v�tame vastu 2 muutujat
	function createUser($create_email, $hash, $firstname, $lastname ){
		
		// Global muutujad, et k�tte saada config failist andmed
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("INSERT INTO users (email, password, first_name, last_name) VALUES (?,?,?,?)");
		$stmt->bind_param("ssss", $create_email, $hash, $firstname, $lastname);
		$stmt->execute();
		$stmt->close();
		
		$mysqli->close();
		
	}
	
	function loginUser($email, $hash){
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);		
		
		$stmt = $mysqli->prepare("SELECT id, email FROM users WHERE email=? AND password=?");
		$stmt->bind_param("ss", $email, $hash);
		$stmt->bind_result($id_from_db, $email_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			// ab'i oli midagi
			echo "Email ja parool �iged, kasutaja id=".$id_from_db;
			
			// tekitan sessiooni muutujad
			$_SESSION["logged_in_user_id"] = $id_from_db;
			$_SESSION["logged_in_user_email"] = $email_from_db;
			
			//suunan data.php lehele
			header("Location: data.php");
			
		}else{
			// ei leidnud
			echo "Wrong e-mail or password!";
		}
		$stmt->close();
		
		$mysqli->close();
	}
			function addPost($arvamus ){
		
		// Global muutujad, et k�tte saada config failist andmed
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("INSERT INTO eestijalgpall (user_id, user_email, post) VALUES (?,?,?)");
		$stmt->bind_param("iis", $_SESSION["logged_in_user_id"], $_SESSION["logged_in_user_email"], $arvamus);
		if($stmt->execute()){
			// kui on t�ene,
			//siis INSERT �nnestus
			$message = "*Sai edukalt lisatud*";
			 
			
		}else{
			// kui on v��rtus FALSE
			// siis kuvame errori
			echo $stmt->error;
			
		}
		
		return $message;
		
		
		$stmt->close();
		
		$mysqli->close();
		
	}
	
?>
	
	

