<?php
include("./class.TemplatePower.inc.php");

$tpl = new TemplatePower("./wall.tpl");
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

	default;

	$query = $db->prepare("SELECT id FROM gebruiker WHERE email=:email");
	$query->bindParam(':email', $_SESSION['email']);
	$query->execute();
	foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row) {
		$id = $row['id'];
	}
	if (isset($_GET['text'])) {
		$text = $_GET['text'];
	}
	else {
		$text = null;
	}
	$tpl->newBlock("default");
	$tpl->assign("ID", $id);
	$tpl->assign("TEXT", $text);

	$query = $db->prepare("SELECT post.*, persoon.voornaam, persoon.achternaam, persoon.avatar, gebruiker.email FROM post INNER JOIN gebruiker ON post.gebruiker_id = gebruiker.id INNER JOIN persoon ON gebruiker.persoon_id = persoon.id WHERE post.status=0 ORDER BY datum desc");
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
      		$delete = $display . ' <a href="wall.php?page=delete&id='.$postid.'"> <img src="delete.gif"  class="delete"> </a> ' . $display2;
      		$edit = $display . ' <a href="wall.php?page=postedit&id='.$postid.'"> <img src="edit.gif"  class="delete"> </a> ' . $display2;
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

	case 'post':
		$query = $db->prepare("INSERT INTO post (content, gebruiker_id, datum) VALUES (:content, :gebruiker_id, :datum)");
		$datum = time() + 3600;
    	$query->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
    	$query->bindParam(':gebruiker_id', $_POST['gebruiker_id'], PDO::PARAM_STR);
    	$query->bindParam(':datum', $datum, PDO::PARAM_INT);
    	$query->execute();
    	$id = $_POST['gebruiker_id'];
        header("Location: wall.php");
	break;

	case 'delete':
		$query = $db->prepare("UPDATE post SET status=1 WHERE id=:id;");
    	$query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    	$query->execute();
    	header("Location: wall.php");
    break;

    case 'postedit':
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
    break;

     case 'postsubmit':
		$query = $db->prepare("UPDATE post SET content=:content WHERE id=:id;");
    	$query->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
    	$query->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
    	$query->execute();
		header("Location: wall.php");
    break;

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
      		$delete = $display . ' <a href="wall.php?page=delete&id='.$postid.'"> <img src="delete.gif"  class="delete"> </a> ' . $display2;
      		$edit = $display . ' <a href="wall.php?page=postedit&id='.$postid.'"> <img src="edit.gif"  class="delete"> </a> ' . $display2;
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
      		$delete = $display . ' <a href="wall.php?page=deletecomment&id='.$postid.'"> <img src="delete.gif"  class="delete"> </a> ' . $display2;
      		$edit = $display . ' <a href="wall.php?page=commentedit&id='.$postid.'"> <img src="edit.gif"  class="delete"> </a> ' . $display2;
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
    	header("Location: wall.php?page=comment&id=$id");
    break;

   	case 'commentpost':
		$query = $db->prepare("INSERT INTO comment (content, post_id, gebruiker_id, datum) VALUES (:content, :post_id, :gebruiker_id, :datum)");
		$datum = time() + 3600;
    	$query->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
    	$query->bindParam(':post_id', $_POST['post_id'], PDO::PARAM_INT);
    	$query->bindParam(':gebruiker_id', $_POST['gebruiker_id'], PDO::PARAM_STR);
    	$query->bindParam(':datum', $datum, PDO::PARAM_INT);
    	$query->execute();
    	$id = $_POST['gebruiker_id'];
        header("Location: wall.php?page=comment&id=".$_POST['post_id']."");
	break;

	case 'commentedit':
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
    break;

    case 'commentsubmit':
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
		header("Location: wall.php?page=comment&id=$id");
    break;

}
}
else {
	header("Location: index.php");
}
$tpl->printToScreen();
?>