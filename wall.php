<?php
include("./class.TemplatePower.inc.php");

$tpl = new TemplatePower("./wall.tpl");
$tpl->prepare();

$db = new PDO('mysql:host=localhost;dbname=demuur', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

include("./wallf.php");

if(isset($_GET['page'])){
  $page = $_GET['page'];
} else {
  $page = null;
}

session_start();
if (isset($_SESSION['id'])) {
switch ($page) {

	default;
	if (isset($_GET['text'])) {
		$text = $_GET['text'];
	}
	else {
		$text = null;
	}
	$tpl->newBlock("default");
	$tpl->assign("ID", $_SESSION['id']);
	$tpl->assign("TEXT", $text);

	$selectPostsResult = selectPosts($db);

      	foreach ($selectPostsResult->fetchAll(PDO::FETCH_ASSOC) as $row) {
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

       		$aantalCommentsResult = aantalComments($db, $row['id']);

	      	$comments = 0;
	      	foreach ($aantalCommentsResult->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$comments = $comments + 1;
			}
			$tpl->assign("COMMENTS", $comments);
      	}

	break;

	case 'post':
	if (isset($_POST['submit'])) {

		postPost($db, $_POST['content'],  $_POST['gebruiker_id']);

    	$id = $_POST['gebruiker_id'];
        header("Location: wall.php");
    }
    else {
    	header("Location: wall.php");
    }
	break;

	case 'delete':
	$checkUserResult = checkUser($db, $_GET['id']);
    $row = $checkUserResult->fetch(PDO::FETCH_ASSOC);
    if($_SESSION['id'] == $row['gebruiker_id']) {
		deletePost($db, $_GET['id']);
    	header("Location: wall.php");
    }
    else {
    	header("Location: wall.php");
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
    	header("Location: wall.php");
    }
    break;

    case 'postsubmit':
    if (isset($_POST['submit'])) {
		updatePost($db, $_POST['content'], $_POST['id']);
		header("Location: wall.php");
	}
    else {
    	header("Location: wall.php");
    }
    break;

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
	$checkUser2Result = checkUser2($db, $_GET['id']);
    $row = $checkUser2Result->fetch(PDO::FETCH_ASSOC);
    if($_SESSION['id'] == $row['gebruiker_id']) {
		$deleteCommentResult = deleteComment($db, $_GET['id']);
    	while($row = $deleteCommentResult->fetch(PDO::FETCH_ASSOC)) {
    	$id = $row['post_id'];
    }
    	header("Location: wall.php?page=comment&id=$id");
    }
    else {
    	header("Location: wall.php");
    }
    break;

   	case 'commentpost':
   	if (isset($_POST['submit'])) {
		postComment($db, $_POST['content'], $_POST['post_id'], $_POST['gebruiker_id']);
    	$id = $_POST['gebruiker_id'];
        header("Location: wall.php?page=comment&id=".$_POST['post_id']."");
    }
    else {
    	header("Location: wall.php");
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
    	header("Location: wall.php");
    }
    break;

    case 'commentsubmit':
    if (isset($_POST['submit'])) {
		$updateCommentResult = updateComment($db, $_POST['id']);
    	while($row = $updateCommentResult->fetch(PDO::FETCH_ASSOC)) {
    	$id = $row['post_id'];
    	}
		header("Location: wall.php?page=comment&id=$id");
	}
    else {
    	header("Location: wall.php");
    }
    break;

}
}
else {
	header("Location: index.php");
}
$tpl->printToScreen();
?>