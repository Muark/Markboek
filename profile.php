<?php
include("./class.TemplatePower.inc.php");

$tpl = new TemplatePower("./profile.tpl");
$tpl->prepare();

$db = new PDO('mysql:host=localhost;dbname=demuur', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

if(isset($_GET['page'])){
  $page = $_GET['page'];
} else {
  $page = null;
}

session_start();
if (isset($_SESSION['email'])) {
switch ($page) {
	case 'comment':
		$query = $db->prepare("SELECT post.*, persoon.voornaam, persoon.achternaam, persoon.avatar, gebruiker.email FROM post INNER JOIN gebruiker ON post.gebruiker_id = gebruiker.id INNER JOIN persoon ON gebruiker.persoon_id = persoon.id WHERE post.id=:postid");
     	$query->bindParam(':postid', $_GET['id'], PDO::PARAM_INT);
      	$query->execute();
      	foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row) {
				$mail = $row['email'];
				if ($mail == $_SESSION['email']) {
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
      	$tpl->assign("CONTENT", $row['content']);
   		$tpl->assign("DATUM",  $datum);
   		$tpl->assign("VOORNAAM", $row['voornaam']);
   		$tpl->assign("ACHTERNAAM", $row['achternaam']);
   		$tpl->assign("VERWIJDER", $delete);
   		$tpl->assign("EDIT", $edit);
   		$tpl->assign("POSTID", $row['id']);
   		$tpl->assign("USERID", $row['gebruiker_id']);

      	$query = $db->prepare("SELECT id FROM gebruiker WHERE email=:email");
     	$query->bindParam(':email', $_SESSION['email'], PDO::PARAM_INT);
      	$query->execute();
      	foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row) {
				$idtje = $row['id'];
			}

   		$tpl->assign("USER", $row['id']);

   		$query = $db->prepare("SELECT comment.*, persoon.voornaam, persoon.achternaam, persoon.avatar, gebruiker.email FROM comment INNER JOIN gebruiker ON comment.gebruiker_id = gebruiker.id INNER JOIN persoon ON gebruiker.persoon_id = persoon.id WHERE comment.post_id=:parentid AND comment.status=0 ORDER BY datum");
     	$query->bindParam(':parentid', $_GET['id'], PDO::PARAM_INT);
      	$query->execute();
      	foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row) {
				$mail = $row['email'];
				if ($mail == $_SESSION['email']) {
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
	      	$tpl->assign("CONTENT", $row['content']);
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
		$query = $db->prepare("INSERT INTO comment (content, post_id, gebruiker_id, datum) VALUES (:content, :post_id, :gebruiker_id, :datum)");
		$datum = time() + 3600;
    	$query->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
    	$query->bindParam(':post_id', $_POST['post_id'], PDO::PARAM_INT);
    	$query->bindParam(':gebruiker_id', $_POST['gebruiker_id'], PDO::PARAM_STR);
    	$query->bindParam(':datum', $datum, PDO::PARAM_INT);
    	$query->execute();
    	$id = $_POST['gebruiker_id'];
        header("Location: profile.php?page=comment&id=".$_POST['post_id']."");
    }
    else {
    	header("Location: profile.php");
    }
	break;


	case 'post':
	if (isset($_POST['submit'])) {
		$query = $db->prepare("INSERT INTO post (content, gebruiker_id, datum) VALUES (:content, :gebruiker_id, :datum)");
		$datum = time() + 3600;
    	$query->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
    	$query->bindParam(':gebruiker_id', $_POST['gebruiker_id'], PDO::PARAM_STR);
    	$query->bindParam(':datum', $datum, PDO::PARAM_INT);
    	$query->execute();
    	$id = $_POST['gebruiker_id'];
        header("Location: profile.php");
    }
    else {
    	header("Location: profile.php");
    }
	break;

	case 'delete':
	$query = $db->prepare("SELECT post.gebruiker_id, gebruiker.email FROM post INNER JOIN gebruiker ON post.gebruiker_id = gebruiker.id WHERE post.id=:id");
    $query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);
    if($_SESSION['email'] == $row['email']) {
		$query = $db->prepare("UPDATE post SET status=1 WHERE id=:id;");
    	$query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    	$query->execute();
    	header("Location: profile.php");
    }
    else {
    	header("Location: profile.php");
    }
    break;

    case 'deletecomment':
    $query = $db->prepare("SELECT comment.gebruiker_id, gebruiker.email FROM comment INNER JOIN gebruiker ON comment.gebruiker_id = gebruiker.id WHERE comment.id=:id");
    $query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);
    if($_SESSION['email'] == $row['email']) {
		$query = $db->prepare("UPDATE comment SET status=1 WHERE id=:id;");
    	$query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    	$query->execute();
    	$query = $db->prepare("SELECT post_id FROM comment WHERE id=:id;");
    	$query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    	$query->execute();
    	while($row = $query->fetch(PDO::FETCH_ASSOC)) {
    	$id = $row['post_id'];
    	}
    	header("Location: profile.php?page=comment&id=$id");
    }
    else {
    	header("Location: profile.php");
    }
    break;

    case 'postedit':
    $query = $db->prepare("SELECT post.gebruiker_id, gebruiker.email FROM post INNER JOIN gebruiker ON post.gebruiker_id = gebruiker.id WHERE post.id=:id");
    $query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);
    if($_SESSION['email'] == $row['email']) {
		$query = $db->prepare("SELECT * FROM post WHERE id=:id;");
    	$query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    	$query->execute();
    	while($row = $query->fetch(PDO::FETCH_ASSOC)) {
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
    $query = $db->prepare("SELECT comment.gebruiker_id, gebruiker.email FROM comment INNER JOIN gebruiker ON comment.gebruiker_id = gebruiker.id WHERE comment.id=:id");
    $query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);
    if($_SESSION['email'] == $row['email']) {
		$query = $db->prepare("SELECT * FROM comment WHERE id=:id;");
    	$query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    	$query->execute();
    	while($row = $query->fetch(PDO::FETCH_ASSOC)) {
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
		$query = $db->prepare("UPDATE post SET content=:content WHERE id=:id;");
    	$query->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
    	$query->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
    	$query->execute();
		header("Location: profile.php");
	}
    else {
    	header("Location: profile.php");
    }
    break;

    case 'commentsubmit':
    if (isset($_POST['submit'])) {
		$query = $db->prepare("UPDATE comment SET content=:content WHERE id=:id;");
    	$query->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
    	$query->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
    	$query->execute();
    	$query = $db->prepare("SELECT post_id FROM comment WHERE id=:id;");
    	$query->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
    	$query->execute();
    	while($row = $query->fetch(PDO::FETCH_ASSOC)) {
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

		$query = $db->prepare("SELECT * FROM gebruiker WHERE email=:email");
 		$query->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
  		$query->execute();
  		$row = $query;
  		while($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$email = $row['email'];
			$persoon_id = $row['persoon_id'];
		}

		$query = $db->prepare("SELECT * FROM persoon WHERE id=:persoon_id");
 		$query->bindParam(':persoon_id', $persoon_id, PDO::PARAM_STR);
  		$query->execute();
  		$row = $query;
  		while($row = $query->fetch(PDO::FETCH_ASSOC)) {
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
	$query = $db->prepare("SELECT * FROM gebruiker WHERE email=:email");
	$query->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
	$query->execute();
	$row = $query;
	while($row = $query->fetch(PDO::FETCH_ASSOC)) {
		$id = $row['persoon_id'];
	}

	if(isset($_POST['voornaam'], $_POST['achternaam'], $_POST['geboortedatum'], $_POST['geslacht'], $_POST['adres'], $_POST['postcode'], $_POST['woonplaats'])){


        $query = $db->prepare("UPDATE persoon SET voornaam=:voornaam, achternaam=:achternaam, geboortedatum=:geboortedatum, geslacht=:geslacht, adres=:adres, postcode=:postcode, woonplaats=:woonplaats, telefoon=:telefoon, mobiel=:mobiel, avatar=:avatar WHERE id=:id");

            
            $geboortedatum = strtotime($_POST['geboortedatum']) + 3600;
        
        $query->bindParam(':voornaam', $_POST['voornaam'], PDO::PARAM_STR);
        $query->bindParam(':achternaam', $_POST['achternaam'], PDO::PARAM_STR);
        $query->bindParam(':geboortedatum', $geboortedatum , PDO::PARAM_INT);
        $query->bindParam(':geslacht', $_POST['geslacht'], PDO::PARAM_STR);
        $query->bindParam(':adres', $_POST['adres'], PDO::PARAM_STR);
        $query->bindParam(':postcode', $_POST['postcode'], PDO::PARAM_STR);
        $query->bindParam(':woonplaats', $_POST['woonplaats'], PDO::PARAM_STR);
        $query->bindParam(':telefoon', $_POST['telefoon'], PDO::PARAM_STR);
        $query->bindParam(':mobiel', $_POST['mobiel'], PDO::PARAM_STR);
        $query->bindParam(':avatar', $_POST['avatar'], PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        
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
			$query = $db->prepare("SELECT id FROM gebruiker WHERE email=:email");
     		$query->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
      		$query->execute();
      		$row = $query;
      		while($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$userid = $row['id'];
			}
		}
		

		$query = $db->prepare("SELECT persoon_id FROM gebruiker WHERE id=:userid");
     	$query->bindParam(':userid', $userid, PDO::PARAM_INT);
      	$query->execute();
      	$row = $query;
      	while($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$persoonid = $row['persoon_id'];
		}

		$query = $db->prepare("SELECT * FROM persoon WHERE id=:persoonid");
     	$query->bindParam(':persoonid', $persoonid, PDO::PARAM_INT);
      	$query->execute();
      	$row = $query;
      	while($row = $query->fetch(PDO::FETCH_ASSOC)) {
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

        $query = $db->prepare("SELECT email FROM gebruiker WHERE id=:userid");
     	$query->bindParam(':userid', $userid, PDO::PARAM_INT);
     	$query->execute();
     	$row = $query;
     	while($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$mail = $row['email'];
			if ($mail == $_SESSION['email']) {
	      		$div = "profile1";
	      	}
	      	else {
	      		$div = "profile2";
	      	}

		}

        $tpl->assign("DIV", $div);


      	$query = $db->prepare("SELECT post.*, persoon.voornaam, persoon.achternaam, persoon.avatar, gebruiker.email FROM post INNER JOIN gebruiker ON post.gebruiker_id = gebruiker.id INNER JOIN persoon ON gebruiker.persoon_id = persoon.id WHERE gebruiker.id=:userid AND post.status=0 ORDER BY datum desc");
     	$query->bindParam(':userid', $userid, PDO::PARAM_INT);
      	$query->execute();
      	foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row) {
				$mail = $row['email'];
				if ($mail == $_SESSION['email']) {
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
	      	$tpl->assign("CONTENT", $row['content']);
       		$tpl->assign("DATUM",  $datum);
       		$tpl->assign("VOORNAAM", $row['voornaam']);
       		$tpl->assign("ACHTERNAAM", $row['achternaam']);
       		$tpl->assign("VERWIJDER", $delete);
       		$tpl->assign("EDIT", $edit);
       		$tpl->assign("POSTID", $row['id']);
       		$tpl->assign("USERID", $row['gebruiker_id']);

       		$query = $db->prepare("SELECT * FROM comment WHERE post_id = :post_id AND status = 0");
	     	$query->bindParam(':post_id', $row['id'], PDO::PARAM_INT);
	      	$query->execute();
	      	$comments = 0;
	      	foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row) {
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