<?php
include("./class.TemplatePower.inc.php");

$tpl = new TemplatePower("./admin.tpl");
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
	$query = $db->prepare("SELECT groep_id FROM gebruiker WHERE email=:email");
	$query->bindParam(':email', $_SESSION['email']);
	$query->execute();
	foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row) {
		$groepid = $row['groep_id'];
	}
	if ($groepid == 2) {
		switch ($page) {
			default;

			$query = $db->prepare("SELECT id FROM gebruiker WHERE email=:email");
			$query->bindParam(':email', $_SESSION['email']);
			$query->execute();
			foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row) {
				$id = $row['id'];
			}
			$tpl->newBlock("default");
			$tpl->assign("ID", $id);

			$query = $db->prepare("SELECT post.*, persoon.voornaam, persoon.achternaam, persoon.avatar, gebruiker.email FROM post INNER JOIN gebruiker ON post.gebruiker_id = gebruiker.id INNER JOIN persoon ON gebruiker.persoon_id = persoon.id WHERE post.status=0 ORDER BY datum desc");
		      	$query->execute();
		      	foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row) {
		      		$postid = $row['id'];
		      		$delete = ' <a href="admin.php?page=delete&id='.$postid.'"> <img src="delete.gif"  class="delete"> </a> ';
		      		$datum = gmdate("d-m-Y", $row['datum']);
		      		$tpl->newBlock("default_post");
			      	$tpl->assign("CONTENT", $row['content']);
		       		$tpl->assign("DATUM",  $datum);
		       		$tpl->assign("VOORNAAM", $row['voornaam']);
		       		$tpl->assign("ACHTERNAAM", $row['achternaam']);
		       		$tpl->assign("VERWIJDER", $delete);
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

			case 'delete':
				$query = $db->prepare("UPDATE post SET status=1 WHERE id=:id;");
		    	$query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		    	$query->execute();
		    	header("Location: admin.php");
		    break;

		    case 'comment':
				$query = $db->prepare("SELECT post.*, persoon.voornaam, persoon.achternaam, persoon.avatar, gebruiker.email FROM post INNER JOIN gebruiker ON post.gebruiker_id = gebruiker.id INNER JOIN persoon ON gebruiker.persoon_id = persoon.id WHERE post.id=:postid");
		     	$query->bindParam(':postid', $_GET['id'], PDO::PARAM_INT);
		      	$query->execute();
		      	foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row) {
		      		$postid = $row['id'];
		      		$delete =' <a href="admin.php?page=delete&id='.$postid.'"> <img src="delete.gif"  class="delete"> </a> ';
		      		$datum = gmdate("d-m-Y", $row['datum']);
		      	}
		      	$tpl->newBlock("comment");
		      	$tpl->assign("CONTENT", $row['content']);
		   		$tpl->assign("DATUM",  $datum);
		   		$tpl->assign("VOORNAAM", $row['voornaam']);
		   		$tpl->assign("ACHTERNAAM", $row['achternaam']);
		   		$tpl->assign("VERWIJDER", $delete);
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
		      		$postid = $row['id'];
		      		$delete = ' <a href="admin.php?page=deletecomment&id='.$postid.'"> <img src="delete.gif"  class="delete"> </a> ';
		      		$datum = gmdate("d-m-Y", $row['datum']);
		      		$tpl->newBlock("default_comment");
			      	$tpl->assign("CONTENT", $row['content']);
		       		$tpl->assign("DATUM",  $datum);
		       		$tpl->assign("VOORNAAM", $row['voornaam']);
		       		$tpl->assign("ACHTERNAAM", $row['achternaam']);
		       		$tpl->assign("VERWIJDER", $delete);
		       		$tpl->assign("POSTID", $row['id']);
		       		$tpl->assign("USERID", $row['gebruiker_id']);
		       	}
			break;

			case 'deletecomment':
				$query = $db->prepare("UPDATE comment SET status=1 WHERE id=:id;");
		    	$query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		    	$query->execute();
		    	$query = $db->prepare("SELECT post_id FROM comment WHERE id=:id;");
		    	$query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		    	$query->execute();
		    	while($row = $query->fetch(PDO::FETCH_ASSOC)) {
		    	$id = $row['post_id'];
		    }
		    	header("Location: admin.php?page=comment&id=$id");
		    break;

		    case 'users';
		    $tpl->newBlock("users");

		    $query = $db->prepare("SELECT gebruiker.*, persoon.voornaam, persoon.achternaam FROM gebruiker INNER JOIN persoon ON gebruiker.persoon_id = persoon.id where status=0 ORDER BY achternaam");
		     $query->execute();
		  	foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row) {
		  		$tpl->newBlock("default_users");
		  		$userid = $row['id'];
		  		$ban = ' <a href="admin.php?page=deleteuser&id='.$userid.'"> <img src="delete.gif"  class="ban"> </a> ';
		  		$tpl->assign("EMAIL", $row['email']);
		   		$tpl->assign("WACHTWOORD",  $row['password']);
		   		$tpl->assign("VOORNAAM", $row['voornaam']);
		   		$tpl->assign("ACHTERNAAM", $row['achternaam']);
		   		$tpl->assign("BAN", $ban);
		   	}
		    break;

		    case 'deleteuser':
				$query = $db->prepare("UPDATE gebruiker SET status=1 WHERE id=:id");
		    	$query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		    	$query->execute();

		    	$query = $db->prepare("UPDATE post SET status=1 WHERE gebruiker_id=:id");
		    	$query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		    	$query->execute();

		    	$query = $db->prepare("UPDATE comment SET status=1 WHERE gebruiker_id=:id");
		    	$query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		    	$query->execute();

		    	header("Location: admin.php?page=users");
		    break;
		}
	}
	else {
		header("Location: wall.php?text=" . "Je hebt geen toegang tot deze pagina." . "");
	}

}
else {
	header("Location: index.php");
}

$tpl->printToScreen();
?>