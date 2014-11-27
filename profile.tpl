<!DOCTYPE html>
<html>
  <head>
    <title>De Muur</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css" />
  </head>

<body>

<!-- START BLOCK : default -->
<div class="all2">
	<div class="header">
	<a href="wall.php" class="link"><img src="logo.jpg" class="logo"></a>
	<h2> Markboek </h2>
	<div class="menu">
	<a href="wall.php" class="link">Home</a> <br>
	<a href="profile.php" class="link">Mijn profiel</a> <br>
	<a href="profile.php?page=edit" class="link">Edit profiel</a> <br>
	<a href="admin.php" class="link">Admin</a> <br>
	<a href="index.php?page=logout" class="link">Logout</a> <br>
	</div>
	</div>
	</div>
<div class="test">
		<div class="left">
			<div class="{DIV}">
			</div>
			<h1> Informatie over deze persoon </h1>
			<p> Foto: </p>
			<img src="{AVATAR}" class="ava">

			<p> Naam: </p>
			{VOORNAAM} {ACHTERNAAM}

			<p> Geslacht: </p>
			{GESLACHT}

			<p> Geboortedatum: </p>
			{GEBOORTEDATUM}

			<p> Woonplaats: </p>
			{WOONPLAATS}
		</div>
		<div class="right">
		<h1> Posts </h1>
		<!-- START BLOCK : default_post -->
			<div class="post">
			<p> <i> <u> <a href="profile.php?userid={USERID}" class="link">{VOORNAAM} {ACHTERNAAM}</a></u>, {DATUM}  </i> {VERWIJDER} {EDIT} </p>
			<p> {CONTENT} </p>
			<p> <a href="profile.php?page=comment&id={POSTID}" class="link">{COMMENTS} Comments >></a> </p>
			</div>
		<!-- END BLOCK : default_post -->
		
		<div class="{DIV}">
			<h3> Plaats een post: </h3>
			<form action="profile.php?page=post" method="post">
				<textarea name= "content"> </textarea>
				<input name= "gebruiker_id" type = "text" value="{ID}" hidden>
				<p class="submit"> <input type = "submit" value = "Post" name = "submit" class="submit"> </p>
			</form>
		</div>
		</div>
		
		</div>
<!-- END BLOCK : default -->


<!-- START BLOCK : edit -->
<div class="all">
	<div class="header">
	<a href="wall.php" class="link"><img src="logo.jpg" class="logo"></a>
	<h2> Markboek </h2>
	<div class="menu">
	<a href="wall.php" class="link">Home</a> <br>
	<a href="profile.php" class="link">Mijn profiel</a> <br>
	<a href="profile.php?page=edit" class="link">Edit profiel</a> <br>
	<a href="admin.php" class="link">Admin</a> <br>
	<a href="index.php?page=logout" class="link">Logout</a> <br>
	</div>
	</div>
<div  style="padding: 10px">
<h1>Edit je profiel</h1>
	{TEXT}
	<form action="profile.php?page=submit" method="post">

		<p> *Wachtwoord: </p> 
		<input name = "password" value="{PASSWORD}" type = "password" required>

		<p> *Wachtwoord herhalen: </p> 
		<input name = "password2" value="{PASSWORD}" type = "password" required>

		<p> *Voornaam: </p> 
		<input name = "voornaam" value="{VOORNAAM}" type = "text" required>

		<p> *Achternaam: </p>
		<input name= "achternaam" value="{ACHTERNAAM}" type = "text" required>

		<p> *Geboortedatum: </p> 
		<input name="geboortedatum" value="{GEBOORTEDATUM}" type="date" required>
		

		<p> *Geslacht: </p>
		<select name="geslacht">
  		<option value="Man">Man</option>
  		<option value="Vrouw">Vrouw</option>
  		</select>

		<p> *Adres: </p>
	  	<input name= "adres" value="{ADRES}" type = "text" required>

  		<p> *Postcode: </p>
  		<input name= "postcode" value="{POSTCODE}" type = "text" required>

  		<p> *Woonplaats: </p>
  		<input name= "woonplaats" value="{WOONPLAATS}" type = "text" required>

  		<p> Telefoon: </p>
  		<input name= "telefoon" value="{TELEFOON}" type = "tel">

  		<p> Mobiel: </p>
  		<input name= "mobiel" value="{MOBIEL}" type = "tel">

		<p> Avatar: </p>
  		<input name= "avatar" value="{AVATAR}" type = "text">

  		<p class="submit"> <input type = "submit" value = "Edit" name = "submit" class="login"> </p>

  		<p> * = Verplicht </p>
	</form>

	<a href="profile.php">Terug</a>
	</div>
	</div>
<!-- END BLOCK : edit -->

<!-- START BLOCK : postedit -->
<div class="all">
	<div class="header">
	<a href="wall.php" class="link"><img src="logo.jpg" class="logo"></a>
	<h2> Markboek </h2>
	<div class="menu">
	<a href="wall.php" class="link">Home</a> <br>
	<a href="profile.php" class="link">Mijn profiel</a> <br>
	<a href="profile.php?page=edit" class="link">Edit profiel</a> <br>
	<a href="admin.php" class="link">Admin</a> <br>
	<a href="index.php?page=logout" class="link">Logout</a> <br>
	</div>
</div>
<div class="postmargin">
<h1> Wijzig je post: </h1>
<form action="profile.php?page=postsubmit" method="post">
	<textarea name= "content"> {CONTENT} </textarea>
	<input name= "id" type = "text" value="{ID}" hidden>
	<p class="submit"> <input type = "submit" value = "Post" name = "submit" class="submit"> </p>
</form>
</div>
</div>
<!-- END BLOCK : postedit -->

<!-- START BLOCK : comment -->
<div class="all">
	<div class="header">
	<a href="wall.php" class="link"><img src="logo.jpg" class="logo"></a>
	<h2> Markboek </h2>
	<div class="menu">
	<a href="wall.php" class="link">Home</a> <br>
	<a href="profile.php" class="link">Mijn profiel</a> <br>
	<a href="profile.php?page=edit" class="link">Edit profiel</a> <br>
	<a href="admin.php" class="link">Admin</a> <br>
	<a href="index.php?page=logout" class="link">Logout</a> <br>
	</div>
	</div>
	<div class="parent">
	<div class="post">
	<p class="parent"> <i> <u> <a href="profile.php?userid={USERID}" class="link">{VOORNAAM} {ACHTERNAAM}</a></u>, {DATUM}  </i> {VERWIJDER} {EDIT} </p>
	<p> {CONTENT} </p>
	</div>
	</div>

	<!-- START BLOCK : default_comment -->
			<div class="comment">
			<div class="post">
			<p> <i> <u> <a href="profile.php?userid={USERID}" class="link">{VOORNAAM} {ACHTERNAAM}</a></u>, {DATUM}  </i> {VERWIJDER} {EDIT} </p>
			<p> {CONTENT} </p>
			</div>
			</div>
		<!-- END BLOCK : default_comment -->
		<div class="comment">
			<h3> Plaats een comment: </h3>
			<form action="profile.php?page=commentpost" method="post">
				<textarea name= "content"> </textarea>
				<input name= "gebruiker_id" type = "text" value="{USER}" hidden>
				<input name= "post_id" type = "text" value="{POSTID}" hidden>
				<p class="submit"> <input type = "submit" value = "Post" name = "submit" class="submit"> </p>
			</form>
		</div>
		</div>
<!-- END BLOCK : comment -->

<!-- START BLOCK : commentedit -->
<div class="all">
	<div class="header">
	<a href="wall.php" class="link"><img src="logo.jpg" class="logo"></a>
	<h2> Markboek </h2>
	<div class="menu">
	<a href="wall.php" class="link">Home</a> <br>
	<a href="profile.php" class="link">Mijn profiel</a> <br>
	<a href="profile.php?page=edit" class="link">Edit profiel</a> <br>
	<a href="admin.php" class="link">Admin</a> <br>
	<a href="index.php?page=logout" class="link">Logout</a> <br>
	</div>
	</div>
<div class="postmargin">
<h1> Wijzig je post: </h1>
<form action="profile.php?page=commentsubmit" method="post">
	<textarea name= "content"> {CONTENT} </textarea>
	<input name= "id" type = "text" value="{ID}" hidden>
	<p class="submit"> <input type = "submit" value = "Post" name = "submit" class="submit"> </p>
</form>
</div>
</div>
<!-- END BLOCK : commentedit -->
</body>