<!DOCTYPE html>
<html>
  <head>
    <title>De Muur</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css" />
  </head>

<body>
	<div class="all">
	<div class="header">
	<a href="wall.php" class="link"><img src="logo.jpg" class="logo"></a>
	<h2> Markboek </h2>
	</div>
	<div class="home">
		<!-- START BLOCK : default -->
		<h1>Login</h1>
		{TEXT}
		<form action="index.php?page=login" method="post">
			<p> Email: </p> <input name= "email" type = "email">
			<p> Wachtwoord: </p> <input name= "password" type = "password">
			<p class="submit"> <input type = "submit" value = "Login" name = "submit" class="login"> </p>
		</form>
		<a href="index.php?page=registreren">Registreren</a>
		<!-- END BLOCK : default -->

		<!-- START BLOCK : registreren -->
		<h1>Registreren</h1>
		{TEXT}
		<form action="index.php?page=registrerensubmit" method="post">
			<p> *Email: </p>
	  		<input name= "email" type = "email" required>

	  		<p> *Wachtwoord: </p>
	  		<input name= "password" type = "password" required>

	  		<p> *Wachtwoord herhalen: </p>
	  		<input name= "password2" type = "password" required>

			<p> *Voornaam: </p> 
			<input name = "voornaam" type = "text" required>

			<p> *Achternaam: </p>
			<input name= "achternaam" type = "text" required>

			<p> *Geboortedatum: </p> 
			<input name="geboortedatum" type="date" required>
			

			<p> *Geslacht: </p>
			<select name="geslacht">
	  		<option value="Man">Man</option>
	  		<option value="Vrouw">Vrouw</option>
	  		</select>

	  		<p> *Adres: </p>
	  		<input name= "adres" type = "text" required>

	  		<p> *Postcode: </p>
	  		<input name= "postcode" type = "text" required>

	  		<p> *Woonplaats: </p>
	  		<input name= "woonplaats" type = "text" required>

	  		<p> Telefoon: </p>
	  		<input name= "telefoon" type = "tel">

	  		<p> Mobiel: </p>
	  		<input name= "mobiel" type = "tel">

	  		<p class="submit"> <input type = "submit" value = "Registreren" name = "submit" class="login"> </p>

	  		<p> * = Verplicht </p>
		</form>

		<a href="index.php">Terug</a>
		<!-- END BLOCK : registreren -->
		</div>
		</div>


</body>

</html>