<?php
include("./class.TemplatePower.inc.php");

$tpl = new TemplatePower("./index.tpl");
$tpl->prepare();

$db = new PDO('mysql:host=localhost;dbname=demuur', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

include("./indexf.php");

if(isset($_GET['page'])){
  $page = $_GET['page'];
} else {
  $page = null;
}

switch ($page) {
	case 'registreren':
		if(isset ($_GET['text'])) {
			$text = "<div class=\"error\">" . $_GET['text'] . "</div>";
		}
		$tpl->newBlock("registreren");
		$tpl->assign("TEXT", $text); 
	break;

	case 'registrerensubmit':
	$emailCheckResult = emailCheck($db, $_POST['email']);

    if(!$row = $emailCheckResult->fetch()) {
    	if($_POST['password'] == $_POST['password2']) {
			if(isset($_POST['voornaam'], $_POST['achternaam'], $_POST['geboortedatum'], $_POST['geslacht'], $_POST['adres'], $_POST['postcode'], $_POST['woonplaats'], $_POST['telefoon'], $_POST['mobiel'], $_POST['email'], $_POST['password'])){


		        registreren1($db, $_POST['geboortedatum'], $_POST['voornaam'], $_POST['achternaam'], $_POST['geslacht'], $_POST['adres'], $_POST['postcode'], $_POST['woonplaats'], $_POST['telefoon'], $_POST['mobiel']);

	        	$LII = $db->lastInsertId();
	        	$stts = 0;
	        	$groepid = 1;

	        	registreren2($db, $_POST['email'], $_POST['password'], $stts, $groepid, $LII);

	            header("Location: index.php?text=Je account is succesvol aangemaakt.");
	            }
		        else {
	    		header("Location: index.php?page=registreren&text=Je hebt een vak verkeerd / niet ingevuld.");
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
	 $loginResult = login($db, $_POST['email'], $_POST['password']);

      if($row = $loginResult->fetch()){
            $_SESSION['id'] = $row['id'];
            header("Location: wall.php");
      }
      else {
      	  header("Location: index.php?text=De gegevens kloppen niet.");
      }

  	}
	break;

	case 'logout':
		session_start();
		unset($_SESSION['id']);
		header("Location:index.php");
	break;
	
	default: 
	session_start();

	if (isset($_SESSION['id'])) {
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