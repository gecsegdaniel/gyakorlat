<?php
	session_start();
	if(!$_SESSION['user']){
		die(header('Location:login.php'));
	}
	
	include_once('includes/setup.php');
	
	if(isset($_POST['addnews'])){
		
		$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRIPPED);
		$text = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRIPPED);
		
		
		if($title != ''){
			$db->insert('news',array('title' => $title,'text' => $text,'public' => $_POST['publical'],'date' => date('Y-m-d H:m:s'),'user_id' => $_SESSION['user']['id']));
			echo '<script>window.location.href="index.php"</script>';
		}	
	}
	
	if(isset($_GET['Logout'])){
		session_destroy();
		echo '<script>window.location.href="index.php"</script>';
	}
	$db->disconnect();
	fejlec();
?>
<a href="index.php">Hírek</a>
<a href="addnews.php">Új hír</a>
<a href="?Logout">Kijelentkezés</a>

<h2>Új hír felvétele</h2>
<form method="post" action="">
<p>
	<label for="title">Cím:</label>
	<input type="text" name="title" />
</p>
<p>
	<label for="publical">No milyen?</label>
	<select name="publical">
		<option value="0">Nincs publikálva</option>
		<option value="1">Publikálva</option>
	</select>
</p>
<p>
	<label for="text">Szöveg:</label>
	<textarea name="text" cols="40" rows="10" style="resize: none;"></textarea>
</p>
<input name="addnews" type="submit" value="Felvisz" />
</form>
<?php
	lablec();
?>