<?php

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

function postComment($db, $content, $postid, $gebruikerid) {
	$query = $db->prepare("INSERT INTO comment (content, post_id, gebruiker_id, datum) VALUES (:content, :post_id, :gebruiker_id, :datum)");
	$datum = time() + 3600;
	$query->bindParam(':content', $content, PDO::PARAM_STR);
	$query->bindParam(':post_id', $postid, PDO::PARAM_INT);
	$query->bindParam(':gebruiker_id', $gebruikerid, PDO::PARAM_STR);
	$query->bindParam(':datum', $datum, PDO::PARAM_INT);
	$query->execute();
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

function deleteComment($db, $id) {
	$query = $db->prepare("UPDATE comment SET status=1 WHERE id=:id;");
	$query->bindParam(':id', $id, PDO::PARAM_INT);
	$query->execute();
	$query = $db->prepare("SELECT post_id FROM comment WHERE id=:id;");
	$query->bindParam(':id', $id, PDO::PARAM_INT);
	$query->execute();
	return $query;
}

function selectEdit($db, $id) {
	$query = $db->prepare("SELECT * FROM post WHERE id=:id;");
	$query->bindParam(':id', $id, PDO::PARAM_INT);
	$query->execute();
	return $query;
}

function commentEdit($db, $id) {
	$query = $db->prepare("SELECT * FROM comment WHERE id=:id;");
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

function selectPersoon($db, $id) {
	$query = $db->prepare("SELECT * FROM gebruiker WHERE id=:id");
 	$query->bindParam(':id', $id, PDO::PARAM_STR);
  	$query->execute();
  	return $query;
}

function selectGegevens($db, $persoon_id) {
	$query = $db->prepare("SELECT * FROM persoon WHERE id=:persoon_id");
 	$query->bindParam(':persoon_id', $persoon_id, PDO::PARAM_STR);
  	$query->execute();
  	return $query;
}

function updateUser($db, $datum, $voornaam, $achternaam, $geslacht, $adres, $postcode, $woonplaats, $telefoon, $mobiel, $avatar, $id, $password) {
	$query = $db->prepare("UPDATE persoon SET voornaam=:voornaam, achternaam=:achternaam, geboortedatum=:geboortedatum, geslacht=:geslacht, adres=:adres, postcode=:postcode, woonplaats=:woonplaats, telefoon=:telefoon, mobiel=:mobiel, avatar=:avatar WHERE id=:id");
      
    $geboortedatum = strtotime($datum) + 3600;
    
    $query->bindParam(':voornaam', $voornaam, PDO::PARAM_STR);
    $query->bindParam(':achternaam', $achternaam, PDO::PARAM_STR);
    $query->bindParam(':geboortedatum', $geboortedatum , PDO::PARAM_INT);
    $query->bindParam(':geslacht', $geslacht, PDO::PARAM_STR);
    $query->bindParam(':adres', $adres, PDO::PARAM_STR);
    $query->bindParam(':postcode', $postcode, PDO::PARAM_STR);
    $query->bindParam(':woonplaats', $woonplaats, PDO::PARAM_STR);
    $query->bindParam(':telefoon', $telefoon, PDO::PARAM_STR);
    $query->bindParam(':mobiel', $mobiel, PDO::PARAM_STR);
    $query->bindParam(':avatar', $avatar, PDO::PARAM_STR);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    $query = $db->prepare("UPDATE gebruiker SET password = :password WHERE persoon_id=:id");
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
      
}

function selectUserID($db, $userid) {
	$query = $db->prepare("SELECT persoon_id FROM gebruiker WHERE id=:userid");
 	$query->bindParam(':userid', $userid, PDO::PARAM_INT);
  	$query->execute();
  	return $query;
}

function selectInfo($db, $persoonid) {
	$query = $db->prepare("SELECT * FROM persoon WHERE id=:persoonid");
    $query->bindParam(':persoonid', $persoonid, PDO::PARAM_INT);
    $query->execute();
    return $query;
}

function selectID($db, $userid) {
    $query = $db->prepare("SELECT id FROM gebruiker WHERE id=:userid");
 	$query->bindParam(':userid', $userid, PDO::PARAM_INT);
 	$query->execute();
 	return $query;
}

function selectInfo2($db, $userid) {
	$query = $db->prepare("SELECT post.*, persoon.voornaam, persoon.achternaam, persoon.avatar FROM post INNER JOIN gebruiker ON post.gebruiker_id = gebruiker.id INNER JOIN persoon ON gebruiker.persoon_id = persoon.id WHERE gebruiker.id=:userid AND post.status=0 ORDER BY datum desc");
 	$query->bindParam(':userid', $userid, PDO::PARAM_INT);
  	$query->execute();
  	return $query;
}

function selectComments($db, $id) {
	$query = $db->prepare("SELECT * FROM comment WHERE post_id = :post_id AND status = 0");
 	$query->bindParam(':post_id', $id, PDO::PARAM_INT);
  	$query->execute();
  	return $query;
}

?>