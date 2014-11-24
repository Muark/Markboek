<?php
include("./class.TemplatePower.inc.php");

$tpl = new TemplatePower("./index.tpl");
$tpl->prepare();

$db = new PDO('mysql:host=localhost;dbname=demuur', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

if(isset($_GET['page'])){
  $page = $_GET['page'];
} else {
  $page = null;
}

switch ($page) {
	case 'registreren':
		if(isset ($_GET['text'])) {
			$text = "<div class=\"error\">" . $_GET['text'] . "</div>";
			$tpl->assign("TEXT", $text); 
		}
		$tpl->newBlock("registreren");
	break;

	case 'registrerensubmit':
	$query = $db->prepare("SELECT email FROM gebruiker WHERE email=:email");
    $query->bindParam(':email', $_POST['email']);
    $query->execute();

    if(!$row = $query->fetch()) {
    	if($_POST['password'] == $_POST['password2']) {
			if(isset($_POST['voornaam'], $_POST['achternaam'], $_POST['geboortedatum'], $_POST['geslacht'], $_POST['adres'], $_POST['postcode'], $_POST['woonplaats'], $_POST['telefoon'], $_POST['mobiel'], $_POST['email'], $_POST['password'])){


		        $query = $db->prepare("INSERT INTO persoon (voornaam, achternaam, geboortedatum, geslacht, adres, postcode, woonplaats, telefoon, mobiel) VALUES (:voornaam, :achternaam, :geboortedatum, :geslacht, :adres, :postcode, :woonplaats, :telefoon, :mobiel)");

		        $geboortedatum = strtotime($_POST['geboortedatum']) + 3600;

		        $query->bindParam(':voornaam', $_POST['voornaam'], PDO::PARAM_STR);
		        $query->bindParam(':achternaam', $_POST['achternaam'], PDO::PARAM_STR);
		        $query->bindParam(':geboortedatum', $geboortedatum , PDO::PARAM_STR);
		        $query->bindParam(':geslacht', $_POST['geslacht'], PDO::PARAM_STR);
		        $query->bindParam(':adres', $_POST['adres'], PDO::PARAM_STR);
		        $query->bindParam(':postcode', $_POST['postcode'], PDO::PARAM_STR);
		        $query->bindParam(':woonplaats', $_POST['woonplaats'], PDO::PARAM_STR);
		        $query->bindParam(':telefoon', $_POST['telefoon'], PDO::PARAM_STR);
		        $query->bindParam(':mobiel', $_POST['mobiel'], PDO::PARAM_STR);

		        if($query->execute()){
		        	$LII = $db->lastInsertId();
		        	$query = $db->prepare("INSERT INTO gebruiker (email, password, status, groep_id, persoon_id) VALUES (:email, :password, :status, :groep_id, :persoon_id)");
		        	$stts = 0;
		        	$groepid = 1;

		        	$query->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
		        	$query->bindParam(':password', $_POST['password'], PDO::PARAM_STR);
		        	$query->bindParam(':status', $stts, PDO::PARAM_INT);
		        	$query->bindParam(':groep_id', $groepid, PDO::PARAM_INT);
		        	$query->bindParam(':persoon_id', $LII, PDO::PARAM_INT);
		            if ($query->execute()) {
		            	header("Location: index.php?text=Je account is succesvol aangemaakt.");
		            }
	            else {
	    		header("Location: index.php?page=registreren&text=Je hebt een veld onjuist ingevoert.");
	    		}
	        } 
	    	}
	    	else {
	    		header("Location: index.php?page=registreren&text=Je hebt een veld onjuist ingevoert.");
	    	}
	    }
	    else {
	    	header("Location: index.php?page=registreren&text=De wachtwoorden komen niet overeen.");
	    }
    }
    else {
    	header("Location: index.php?page=registreren&text=Dit email is al in gebruik.");
    }
	break;

	case 'login':
	if(isset($_POST['email'], $_POST['password'])){
	  session_start();
	  $query = $db->prepare("SELECT email, password FROM gebruiker WHERE email=:email AND password=:password");
      $query->bindParam(':email', $_POST['email']);
      $query->bindParam(':password', $_POST['password']);
      $query->execute();

      if($row = $query->fetch()){
            $_SESSION['email'] = $row['email'];
            header("Location: wall.php");
      }
      else {
      	  header("Location: index.php?text=De gegevens kloppen niet.");
      }

  	}
	break;

	case 'logout':
		session_start();
		unset($_SESSION['email']);
		header("Location:index.php");
	break;
	
	default: 
	session_start();

	if (isset($_SESSION['email'])) {
		header("Location: wall.php");
	}

	else {
		$tpl->newBlock("default");
		if(isset ($_GET['text'])) {
			if ($_GET['text'] == "Je account is succesvol aangemaakt.") {
				$text = "<div class=\"goed\">" . $_GET['text'] . "</div>";
			}
			else {
			$text = "<div class=\"error\">" . $_GET['text'] . "</div>";
			}
			$tpl->assign("TEXT", $text); 
		}
	}

	break;
}

$tpl->printToScreen();

?>