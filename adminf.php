<?php

function checkAdmin($db, $id) {
	$query = $db->prepare("SELECT groep_id FROM gebruiker WHERE id=:id");
	$query->bindParam(':id', $id);
	$query->execute();
	return $query;
}

function selectPosts($db) {
	$query = $db->prepare("SELECT post.*, persoon.voornaam, persoon.achternaam, persoon.avatar, gebruiker.email FROM post INNER JOIN gebruiker ON post.gebruiker_id = gebruiker.id INNER JOIN persoon ON gebruiker.persoon_id = persoon.id WHERE post.status=0 ORDER BY datum desc");
	$query->execute();
	return $query;
}

function aantalComments($db, $id) {
	$query = $db->prepare("SELECT * FROM comment WHERE post_id = :post_id AND status = 0");
	$query->bindParam(':post_id', $id, PDO::PARAM_INT);
	$query->execute();
	return $query;
}

function deletePost($db, $id) {
	$query = $db->prepare("UPDATE post SET status=1 WHERE id=:id");
	$query->bindParam(':id', $id, PDO::PARAM_INT);
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

function selectUsers($db) {
	$query = $db->prepare("SELECT gebruiker.*, persoon.voornaam, persoon.achternaam FROM gebruiker INNER JOIN persoon ON gebruiker.persoon_id = persoon.id where status=0 ORDER BY achternaam");
	$query->execute();
	return $query;
}

function deleteUser($db, $id) {
	$query = $db->prepare("UPDATE gebruiker SET status=1 WHERE id=:id");
	$query->bindParam(':id', $id, PDO::PARAM_INT);
	$query->execute();

	$query = $db->prepare("UPDATE post SET status=1 WHERE gebruiker_id=:id");
	$query->bindParam(':id', $id, PDO::PARAM_INT);
	$query->execute();

	$query = $db->prepare("UPDATE comment SET status=1 WHERE gebruiker_id=:id");
	$query->bindParam(':id', $id, PDO::PARAM_INT);
	$query->execute();
}

?>