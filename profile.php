<?php
include("./class.TemplatePower.inc.php");

$tpl = new TemplatePower("./profile.tpl");
$tpl->prepare();

$db = new PDO('mysql:host=localhost;dbname=demuur', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

include("./profilef.php"); 

if(isset($_GET['page'])){
  $page = $_GET['page'];
} else {
  $page = null;
}

session_start();
if (isset($_SESSION['id'])) {
switch ($page) {
	case 'comment':
		$selectParentResult = selectParent($db, $_GET['id']);
      	foreach ($selectParentResult->fetchAll(PDO::FETCH_ASSOC) as $row) {
      			$gebruiker_id = $row['gebruiker_id'];
				if ($gebruiker_id == $_SESSION['id']) {
		      		$display = "";
		      		$display2 = "";
		      	}
		      	else {
		      		$display = '<div class="delete2"';
		      		$display2 = '</div>';
		      	}
      		$postid = $row['id'];
      		$delete = $display . ' <a href="profile.php?page=delete&id='.$postid.'"> <img src="delete.gif"  class="delete"> </a> ' . $display2;
      		$edit = $display . ' <a href="profile.php?page=postedit&id='.$postid.'"> <img src="edit.gif"  class="delete"> </a> ' . $display2;
      		$datum = gmdate("d-m-Y", $row['datum']);
      	}
      	$tpl->newBlock("comment");
      	$content = nl2br(htmlentities($row['content'], ENT_QUOTES, 'UTF-8'));
	    $tpl->assign("CONTENT", $content);
   		$tpl->assign("DATUM",  $datum);
   		$tpl->assign("VOORNAAM", $row['voornaam']);
   		$tpl->assign("ACHTERNAAM", $row['achternaam']);
   		$tpl->assign("VERWIJDER", $delete);
   		$tpl->assign("EDIT", $edit);
   		$tpl->assign("POSTID", $row['id']);
   		$tpl->assign("USERID", $row['gebruiker_id']);

   		$tpl->assign("USER", $_SESSION['id']);

   		$selectCommentResult = selectComment($db, $_GET['id']);
      	foreach ($selectCommentResult->fetchAll(PDO::FETCH_ASSOC) as $row) {
				$gebruiker_id = $row['gebruiker_id'];
				if ($gebruiker_id == $_SESSION['id']) {
		      		$display = "";
		      		$display2 = "";
		      	}
		      	else {
		      		$display = '<div class="delete2"';
		      		$display2 = '</div>';
		      	}
      		$postid = $row['id'];
      		$delete = $display . ' <a href="profile.php?page=deletecomment&id='.$postid.'"> <img src="delete.gif"  class="delete"> </a> ' . $display2;
      		$edit = $display . ' <a href="profile.php?page=commentedit&id='.$postid.'"> <img src="edit.gif"  class="delete"> </a> ' . $display2;
      		$datum = gmdate("d-m-Y", $row['datum']);
      		$tpl->newBlock("default_comment");
	      	$content = nl2br(htmlentities($row['content'], ENT_QUOTES, 'UTF-8'));
	      	$tpl->assign("CONTENT", $content);
       		$tpl->assign("DATUM",  $datum);
       		$tpl->assign("VOORNAAM", $row['voornaam']);
       		$tpl->assign("ACHTERNAAM", $row['achternaam']);
       		$tpl->assign("VERWIJDER", $delete);
       		$tpl->assign("EDIT", $edit);
       		$tpl->assign("POSTID", $row['id']);
       		$tpl->assign("USERID", $row['gebruiker_id']);
       	}
	break;

	case 'commentpost':
	if(isset($_POST['submit'])) {
		postComment($db, $_POST['content'], $_POST['post_id'], $_POST['gebruiker_id']);
    	$id = $_POST['gebruiker_id'];
        header("Location: profile.php?page=comment&id=".$_POST['post_id']."");
    }
    else {
    	header("Location: profile.php");
    }
	break;


	case 'post':
	if (isset($_POST['submit'])) {
		postPost($db, $_POST['content'],  $_POST['gebruiker_id']);
    	$id = $_POST['gebruiker_id'];
        header("Location: profile.php");
    }
    else {
    	header("Location: profile.php");
    }
	break;

	case 'delete':
	$checkUserResult = checkUser($db, $_GET['id']);
    $row = $checkUserResult->fetch(PDO::FETCH_ASSOC);
    if($_SESSION['id'] == $row['gebruiker_id']) {
		deletePost($db, $_GET['id']);
    	header("Location: profile.php");
    }
    else {
    	header("Location: profile.php");
    }
    break;

    case 'deletecomment':
    $checkUser2Result = checkUser2($db, $_GET['id']);
    $row = $checkUser2Result->fetch(PDO::FETCH_ASSOC);
    if($_SESSION['id'] == $row['gebruiker_id']) {
		$deleteCommentResult = deleteComment($db, $_GET['id']);
    	while($row = $deleteCommentResult->fetch(PDO::FETCH_ASSOC)) {
    	$id = $row['post_id'];
    }
    	header("Location: profile.php?page=comment&id=$id");
    }
    else {
    	header("Location: profile.php");
    }
    break;

    case 'postedit':
    $checkUserResult = checkUser($db, $_GET['id']);
    $row = $checkUserResult->fetch(PDO::FETCH_ASSOC);
    if($_SESSION['id'] == $row['gebruiker_id']) {
		$selectEditResult = selectEdit($db, $_GET['id']);
    	while($row = $selectEditResult->fetch(PDO::FETCH_ASSOC)) {
    		$id = $row['id'];
			$content = $row['content'];
		}
		$tpl->newBlock("postedit");
		$tpl->assign("ID", $id); 
		$tpl->assign("CONTENT", $content); 
	}
    else {
    	header("Location: profile.php");
    }
    break;

    case 'commentedit':
    $checkUser2Result = checkUser2($db, $_GET['id']);
    $row = $checkUser2Result->fetch(PDO::FETCH_ASSOC);
    if($_SESSION['id'] == $row['gebruiker_id']) {
		$commentEditResult = commentEdit($db, $_GET['id']);
    	while($row = $commentEditResult->fetch(PDO::FETCH_ASSOC)) {
    		$id = $row['id'];
			$content = $row['content'];
		}
		$tpl->newBlock("commentedit");
		$tpl->assign("ID", $id); 
		$tpl->assign("CONTENT", $content); 
	}
    else {
    	header("Location: profile.php");
    }
    break;

    case 'postsubmit':
    if (isset($_POST['submit'])) {
		updatePost($db, $_POST['content'], $_POST['id']);
		header("Location: profile.php");
	}
    else {
    	header("Location: profile.php");
    }
    break;

    case 'commentsubmit':
    if (isset($_POST['submit'])) {
		$updateCommentResult = updateComment($db, $_POST['id']);
    	while($row = $updateCommentResult->fetch(PDO::FETCH_ASSOC)) {
    	$id = $row['post_id'];
    	}
		header("Location: profile.php?page=comment&id=$id");
	}
    else {
    	header("Location: profile.php");
    }
    break;

	case 'edit':
		$text = null;
		if(isset ($_GET['text'])) {
		$text = "<div class=\"error\">" . $_GET['text'] . "</div>";
		}

		$selectPersoonResult = selectPersoon($db, $_SESSION['id']);
  		while($row = $selectPersoonResult->fetch(PDO::FETCH_ASSOC)) {
			$email = $row['email'];
			$persoon_id = $row['persoon_id'];
		}

		$selectGegevensResult = selectGegevens($db, $persoon_id);
  		while($row = $selectGegevensResult->fetch(PDO::FETCH_ASSOC)) {
			$voornaam = $row['voornaam'];
			$achternaam = $row['achternaam'];
			$geboortedatum = $row['geboortedatum'];
			$adres = $row['adres'];
			$postcode = $row['postcode'];
			$woonplaats = $row['woonplaats'];
			$telefoon = $row['telefoon'];
			$mobiel = $row['mobiel'];
			$avatar = $row['avatar'];
		}
		$geboortedatum2 = gmdate("Y-m-d", $geboortedatum);
		$tpl->newBlock("edit");
		$tpl->assign("EMAIL", $email); 
        $tpl->assign("VOORNAAM", $voornaam); 
        $tpl->assign("ACHTERNAAM", $achternaam); 
        $tpl->assign("GEBOORTEDATUM", $geboortedatum2);
        $tpl->assign("ADRES", $adres);
        $tpl->assign("POSTCODE", $postcode);
        $tpl->assign("WOONPLAATS", $woonplaats);
        $tpl->assign("TELEFOON", $telefoon);
        $tpl->assign("MOBIEL", $mobiel);
        $tpl->assign("AVATAR", $avatar);
        $tpl->assign("TEXT", $text); 
	break;

	case 'submit':
	if (isset($_POST['submit'])) {
		$selectPersoonResult = selectPersoon($db, $_SESSION['id']);
		while($row = $selectPersoonResult->fetch(PDO::FETCH_ASSOC)) {
			$id = $row['persoon_id'];
		}
		if(isset($_POST['voornaam'], $_POST['achternaam'], $_POST['geboortedatum'], $_POST['geslacht'], $_POST['adres'], $_POST['postcode'], $_POST['woonplaats'])){

			updateUser($db, $_POST['geboortedatum'], $_POST['voornaam'], $_POST['achternaam'], $_POST['geslacht'], $_POST['adres'], $_POST['postcode'], $_POST['woonplaats'], $_POST['telefoon'], $_POST['mobiel'], $_POST['avatar'], $id);
		    
			header("Location: profile.php");	
		} 
		else {
			header("Location: profile.php?page=edit&text=Je hebt een veld onjuist ingevoert.");
		}
	}
    else {
    	header("Location: profile.php");
    }
	break;

	default;
		if (isset($_GET['userid'])) {
			$userid = $_GET['userid'];
		}
		else {
			$userid = $_SESSION['id'];
		}
		

		$selectUserIDResult = selectUserID($db, $userid);
      	while($row = $selectUserIDResult->fetch(PDO::FETCH_ASSOC)) {
			$persoonid = $row['persoon_id'];
		}

		$selectInfoResult = selectInfo($db, $persoonid);
      	while($row = $selectInfoResult->fetch(PDO::FETCH_ASSOC)) {
	      	$avatar = $row['avatar'];
	      	$voornaam = $row['voornaam'];
	      	$achternaam = $row['achternaam'];
	      	$geboortedatum = $row['geboortedatum'];
	      	$geslacht = $row['geslacht'];
	      	$woonplaats = $row['woonplaats'];
      	}
      	$geboortedatum2 = gmdate("d-m-Y", $geboortedatum);

      	$tpl->newBlock("default");
      	$tpl->assign("ID", $userid); 
		$tpl->assign("AVATAR", $avatar); 
        $tpl->assign("VOORNAAM", $voornaam); 
        $tpl->assign("ACHTERNAAM", $achternaam); 
        $tpl->assign("GEBOORTEDATUM", $geboortedatum2);
        $tpl->assign("GESLACHT", $geslacht);
        $tpl->assign("WOONPLAATS", $woonplaats);

        $selectIDResult = selectID($db, $userid);
     	while($row = $selectIDResult->fetch(PDO::FETCH_ASSOC)) {
			$gebruiker_id = $row['id'];
			if ($gebruiker_id == $_SESSION['id']) {
	      		$div = "profile1";
	      	}
	      	else {
	      		$div = "profile2";
	      	}

		}

        $tpl->assign("DIV", $div);


      	$selectInfo2Result = selectInfo2($db, $userid);
      	foreach ($selectInfo2Result->fetchAll(PDO::FETCH_ASSOC) as $row) {
				$gebruiker_id = $row['gebruiker_id'];
				if ($gebruiker_id == $_SESSION['id']) {
		      		$display = "";
		      		$display2 = "";
		      	}
		      	else {
		      		$display = '<div class="delete2"';
		      		$display2 = '</div>';
		      	}
      		$postid = $row['id'];
      		$delete = $display . ' <a href="profile.php?page=delete&id='.$postid.'"> <img src="delete.gif"  class="delete"> </a> ' . $display2;
      		$edit = $display . ' <a href="profile.php?page=postedit&id='.$postid.'"> <img src="edit.gif"  class="delete"> </a> ' . $display2;
      		$datum = gmdate("d-m-Y", $row['datum']);
      		$tpl->newBlock("default_post");
			$content = nl2br(htmlentities($row['content'], ENT_QUOTES, 'UTF-8'));
	      	$tpl->assign("CONTENT", $content);
       		$tpl->assign("DATUM",  $datum);
       		$tpl->assign("VOORNAAM", $row['voornaam']);
       		$tpl->assign("ACHTERNAAM", $row['achternaam']);
       		$tpl->assign("VERWIJDER", $delete);
       		$tpl->assign("EDIT", $edit);
       		$tpl->assign("POSTID", $row['id']);
       		$tpl->assign("USERID", $row['gebruiker_id']);

       		$selectCommentsResult = selectComments($db, $row['id']);
	      	$comments = 0;
	      	foreach ($selectCommentsResult->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$comments = $comments + 1;
			}
			$tpl->assign("COMMENTS", $comments);
      	}


		

	break;
}



}

else {
	header("Location: index.php");
}

$tpl->printToScreen();
?>