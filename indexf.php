<?php

function emailCheck($db, $email) {
	$query = $db->prepare("SELECT email FROM gebruiker WHERE email=:email");
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    return $query;
}

function registreren1($db, $datum, $voornaam, $achternaam, $geslacht, $adres, $postcode, $woonplaats, $telefoon, $mobiel) {
	$query = $db->prepare("INSERT INTO persoon (voornaam, achternaam, geboortedatum, geslacht, adres, postcode, woonplaats, telefoon, mobiel) VALUES (:voornaam, :achternaam, :geboortedatum, :geslacht, :adres, :postcode, :woonplaats, :telefoon, :mobiel)");

    $geboortedatum = strtotime($datum) + 3600;

    $query->bindParam(':voornaam', $voornaam, PDO::PARAM_STR);
    $query->bindParam(':achternaam', $achternaam, PDO::PARAM_STR);
    $query->bindParam(':geboortedatum', $geboortedatum , PDO::PARAM_STR);
    $query->bindParam(':geslacht', $geslacht, PDO::PARAM_STR);
    $query->bindParam(':adres', $adres, PDO::PARAM_STR);
    $query->bindParam(':postcode', $postcode, PDO::PARAM_STR);
    $query->bindParam(':woonplaats', $woonplaats, PDO::PARAM_STR);
    $query->bindParam(':telefoon', $telefoon, PDO::PARAM_STR);
    $query->bindParam(':mobiel', $mobiel, PDO::PARAM_STR);
	$query->execute();
    return $query;
}

function registreren2($db, $email, $password, $stts, $groepid, $LII) {
	$query = $db->prepare("INSERT INTO gebruiker (email, password, status, groep_id, persoon_id) VALUES (:email, :password, :status, :groep_id, :persoon_id)");
	        	
	$query->bindParam(':email', $email, PDO::PARAM_STR);
	$query->bindParam(':password', $password, PDO::PARAM_STR);
	$query->bindParam(':status', $stts, PDO::PARAM_INT);
	$query->bindParam(':groep_id', $groepid, PDO::PARAM_INT);
	$query->bindParam(':persoon_id', $LII, PDO::PARAM_INT);
    $query->execute();
}

function login($db, $email, $password) {
	$query = $db->prepare("SELECT id FROM gebruiker WHERE email=:email AND password=:password");
	$query->bindParam(':email', $email);
	$query->bindParam(':password', $password);
	$query->execute();
	return $query;
}

?>