<?php
include("./class.TemplatePower.inc.php");

$tpl = new TemplatePower("./admin.tpl");
$tpl->prepare();

$db = new PDO('mysql:host=localhost;dbname=demuur', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

include("./adminf.php");

if(isset($_GET['page'])){
  $page = $_GET['page'];
} else {
  $page = null;
}

session_start();

if (isset($_SESSION['id'])) {
	$checkAdminResult = checkAdmin($db, $_SESSION['id']);
	foreach ($checkAdminResult->fetchAll(PDO::FETCH_ASSOC) as $row) {
		$groepid = $row['groep_id'];
	}
	if ($groepid == 2) {
		switch ($page) {
			default;

			$tpl->newBlock("default");
			$tpl->assign("ID", $_SESSION['id']);

			$selectPostsResult = selectPosts($db);
		      	foreach ($selectPostsResult->fetchAll(PDO::FETCH_ASSOC) as $row) {
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

		       		$aantalCommentsResult = aantalComments($db, $row['id']);
			      	$comments = 0;
			      	foreach ($aantalCommentsResult->fetchAll(PDO::FETCH_ASSOC) as $row) {
							$comments = $comments + 1;
					}
					$tpl->assign("COMMENTS", $comments);
		      	}

			break;

			case 'delete':
				deletePost($db, $_GET['id']);
		    	header("Location: admin.php");
		    break;

		    case 'comment':
				$selectParentResult = selectParent($db, $_GET['id']);
      			foreach ($selectParentResult->fetchAll(PDO::FETCH_ASSOC) as $row) {
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


		   		$tpl->assign("USER", $_SESSION['id']);

		   		$selectCommentResult = selectComment($db, $_GET['id']);
      			foreach ($selectCommentResult->fetchAll(PDO::FETCH_ASSOC) as $row) {
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
				$deleteCommentResult = deleteComment($db, $_GET['id']);
    			while($row = $deleteCommentResult->fetch(PDO::FETCH_ASSOC)) {
		    	$id = $row['post_id'];
		    }
		    	header("Location: admin.php?page=comment&id=$id");
		    break;

		    case 'users';
		   	$tpl->newBlock("users");
		    $selectUsersResult = selectUsers($db);
		  	foreach ($selectUsersResult->fetchAll(PDO::FETCH_ASSOC) as $row) {
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
				deleteUser($db, $_GET['id']);
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