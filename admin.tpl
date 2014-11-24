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
	<div class="menu">
	<a href="wall.php" class="link">Home</a> <br>
	<a href="profile.php" class="link">Mijn profiel</a> <br>
	<a href="admin.php" class="link">Admin</a> <br>
	<a href="index.php?page=logout" class="link">Logout</a> <br>
	</div>
	<div class="admin">
	<i> Admin </i> <br>
	<a href="admin.php" class="link">Posts</a> <br>
	<a href="admin.php?page=users" class="link">Users</a> <br>
	</div>
	<div class="index">
	<!-- START BLOCK : default -->
			<h1> Posts </h1>
		<!-- START BLOCK : default_post -->
			<div class="post">
			<p> <i> <u> <a href="profile.php?userid={USERID}" class="link">{VOORNAAM} {ACHTERNAAM}</a></u>, {DATUM}  </i> {VERWIJDER} </p>
			<p> {CONTENT} </p>
			<p> <a href="admin.php?page=comment&id={POSTID}" class="link">{COMMENTS} Comments >></a> </p>
			</div>
		<!-- END BLOCK : default_post -->
	<!-- END BLOCK : default -->

	<!-- START BLOCK : comment -->
	<div class="parent">
	<div class="post">
	<p class="parent"> <i> <u> <a href="wall.php?userid={USERID}" class="link">{VOORNAAM} {ACHTERNAAM}</a></u>, {DATUM}  </i> {VERWIJDER}</p>
	<p> {CONTENT} </p>
	</div>
	</div>

	<!-- START BLOCK : default_comment -->
			<div class="comment">
			<div class="post">
			<p> <i> <u> <a href="wall.php?userid={USERID}" class="link">{VOORNAAM} {ACHTERNAAM}</a></u>, {DATUM}  </i> {VERWIJDER} </p>
			<p> {CONTENT} </p>
			</div>
			</div>
		<!-- END BLOCK : default_comment -->
<!-- END BLOCK : comment -->

<!-- START BLOCK : users -->
			<h1> Users </h1>
			<table>
				<tr>
					<th> Email </th>
					<th> Wachtwoord </th>
					<th> Voornaam </th>
					<th> Achternaam  </th>
					<th> Ban </th>
				</tr>
		<!-- START BLOCK : default_users -->
				<tr>
					<td> {EMAIL} </td>
					<td> {WACHTWOORD} </td>
					<td> {VOORNAAM} </td>
					<td> {ACHTERNAAM}  </td>
					<td> {BAN} </td>
				</tr>
		<!-- END BLOCK : default_users -->
		</table>
	<!-- END BLOCK : users -->
	</div>
</body>