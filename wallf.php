<?php	

function selectPosts($db) {
	$query = $db->prepare("SELECT post.*, persoon.voornaam, persoon.achternaam, persoon.avatar FROM post INNER JOIN gebruiker ON post.gebruiker_id = gebruiker.id INNER JOIN persoon ON gebruiker.persoon_id = persoon.id WHERE post.status=0 ORDER BY datum desc");
    $query->execute();
    return $query;
}

function aantalComments($db, $id) {
	$query = $db->prepare("SELECT * FROM comment WHERE post_id = :post_id AND status = 0");
	$query->bindParam(':post_id', $id, PDO::PARAM_INT);
	$query->execute();
	return $query;
}

function postPost($db, $content, $gebruiker) {
	$query = $db->prepare("INSERT INTO post (content, gebruiker_id, datum) VALUES (:content, :gebruiker_id, :datum)");
	$datum = time() + 3600;
	$query->bindParam(':content', $content, PDO::PARAM_STR);
	$query->bindParam(':gebruiker_id',$gebruiker, PDO::PARAM_STR);
	$query->bindParam(':datum', $datum, PDO::PARAM_INT);
	$query->execute();
}

function checkUser($db, $id) {
	$query = $db->prepare("SELECT post.gebruiker_id FROM post WHERE post.id=:id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    return $query;
}

function checkUser2($db, $id) {
	$query = $db->prepare("SELECT comment.gebruiker_id FROM comment WHERE comment.id=:id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    return $query;
}

function deletePost($db, $id) {
	$query = $db->prepare("UPDATE post SET status=1 WHERE id=:id");
	$query->bindParam(':id', $id, PDO::PARAM_INT);
	$query->execute();
}

function selectEdit($db, $id) {
	$query = $db->prepare("SELECT * FROM post WHERE id=:id;");
	$query->bindParam(':id', $id, PDO::PARAM_INT);
	$query->execute();
	return $query;
}

function updatePost($db, $content, $gebruiker) {
	$query = $db->prepare("UPDATE post SET content=:content WHERE id=:id;");
	$query->bindParam(':content', $content, PDO::PARAM_STR);
	$query->bindParam(':id', $gebruiker, PDO::PARAM_INT);
	$query->execute();
}

function selectParent($db, $id) {
	$query = $db->prepare("SELECT post.*, persoon.voornaam, persoon.achternaam, persoon.avatar FROM post INNER JOIN gebruiker ON post.gebruiker_id = gebruiker.id INNER JOIN persoon ON gebruiker.persoon_id = persoon.id WHERE post.id=:postid");
 	$query->bindParam(':postid', $id, PDO::PARAM_INT);
  	$query->execute();
  	return $query;
}

function selectComment($db, $id) {
	$query = $db->prepare("SELECT comment.*, persoon.voornaam, persoon.achternaam, persoon.avatar FROM comment INNER JOIN gebruiker ON comment.gebruiker_id = gebruiker.id INNER JOIN persoon ON gebruiker.persoon_id = persoon.id WHERE comment.post_id=:parentid AND comment.status=0 ORDER BY datum");
 	$query->bindParam(':parentid', $id, PDO::PARAM_INT);
  	$query->execute();
  	return $query;
}

function deleteComment($db, $id) {
	$query = $db->prepare("UPDATE comment SET status=1 WHERE id=:id;");
	$query->bindParam(':id', $id, PDO::PARAM_INT);
	$query->execute();
	$query = $db->prepare("SELECT post_id FROM comment WHERE id=:id;");
	$query->bindParam(':id', $id, PDO::PARAM_INT);
	$query->execute();
	return $query;
}

function postComment($db, $content, $postid, $gebruikerid) {
	$query = $db->prepare("INSERT INTO comment (content, post_id, gebruiker_id, datum) VALUES (:content, :post_id, :gebruiker_id, :datum)");
	$datum = time() + 3600;
	$query->bindParam(':content', $content, PDO::PARAM_STR);
	$query->bindParam(':post_id', $postid, PDO::PARAM_INT);
	$query->bindParam(':gebruiker_id', $gebruikerid, PDO::PARAM_STR);
	$query->bindParam(':datum', $datum, PDO::PARAM_INT);
	$query->execute();
}

function commentEdit($db, $id) {
	$query = $db->prepare("SELECT * FROM comment WHERE id=:id;");
	$query->bindParam(':id', $id, PDO::PARAM_INT);
	$query->execute();
	return $query;
}

function updateComment($db, $id) {
	$query = $db->prepare("UPDATE comment SET content=:content WHERE id=:id;");
	$query->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
	$query->bindParam(':id', $id, PDO::PARAM_INT);
	$query->execute();
	$query = $db->prepare("SELECT post_id FROM comment WHERE id=:id;");
	$query->bindParam(':id', $id, PDO::PARAM_INT);
	$query->execute();
	return $query;
}

?>