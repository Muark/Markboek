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
	<a href="profile.php?page=edit" class="link">Edit profiel</a> <br>
	<a href="admin.php" class="link">Admin</a> <br>
	<a href="index.php?page=logout" class="link">Logout</a> <br>
	</div>
	<div class="index">
	<!-- START BLOCK : default -->
	<h3 style="color:red">{TEXT}</h3>
	<h1> Plaats een post: </h1>
			<form action="wall.php?page=post" method="post">
				<textarea name= "content"> </textarea>
				<input name= "gebruiker_id" type = "text" value="{ID}" hidden>
				<p class="submit"> <input type = "submit" value = "Post" name = "submit" class="submit"> </p>
			</form>
			{LIKES}
			<h1> Posts </h1>
		<!-- START BLOCK : default_post -->
			<div class="post">
			<p> <i> <u> <a href="profile.php?userid={USERID}" class="link">{VOORNAAM} {ACHTERNAAM}</a></u>, {DATUM}  </i> {VERWIJDER} {EDIT} </p>
			<p> {CONTENT} </p>
			<p> <a href="wall.php?page=comment&id={POSTID}" class="link">{COMMENTS} Comments >></a> </p>
			</div>
		<!-- END BLOCK : default_post -->
	<!-- END BLOCK : default -->

	<!-- START BLOCK : postedit -->
<div class="postmargin">
<h1> Wijzig je post: </h1>
<form action="wall.php?page=postsubmit" method="post">
	<textarea name= "content"> {CONTENT} </textarea>
	<input name= "id" type = "text" value="{ID}" hidden>
	<p class="submit"> <input type = "submit" value = "Post" name = "submit"> </p>
</form>
</div>
<!-- END BLOCK : postedit -->

<!-- START BLOCK : comment -->
	<div class="parent">
	<div class="post">
	<p class="parent"> <i> <u> <a href="wall.php?userid={USERID}" class="link">{VOORNAAM} {ACHTERNAAM}</a></u>, {DATUM}  </i> {VERWIJDER} {EDIT} </p>
	<p> {CONTENT} </p>
	</div>
	</div>

	<!-- START BLOCK : default_comment -->
			<div class="comment">
			<div class="post">
			<p> <i> <u> <a href="wall.php?userid={USERID}" class="link">{VOORNAAM} {ACHTERNAAM}</a></u>, {DATUM}  </i> {VERWIJDER} {EDIT} </p>
			<p> {CONTENT} </p>
			</div>
			</div>
		<!-- END BLOCK : default_comment -->
		<div class="comment">
			<h3> Plaats een comment: </h3>
			<form action="wall.php?page=commentpost" method="post">
				<textarea name= "content"> </textarea>
				<input name= "gebruiker_id" type = "text" value="{USER}" hidden>
				<input name= "post_id" type = "text" value="{POSTID}" hidden>
				<p class="submit"> <input type = "submit" value = "Post" name = "submit" class="submit"> </p>
			</form>
		</div>
<!-- END BLOCK : comment -->

<!-- START BLOCK : commentedit -->
<div class="postmargin">
<h1> Wijzig je post: </h1>
<form action="wall.php?page=commentsubmit" method="post">
	<textarea name= "content"> {CONTENT} </textarea>
	<input name= "id" type = "text" value="{ID}" hidden>
	<p class="submit"> <input type = "submit" value = "Post" name = "submit" class="submit"> </p>
</form>
</div>
<!-- END BLOCK : commentedit -->

</div>
</body>