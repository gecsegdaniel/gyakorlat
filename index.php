<?php
	session_start();
	include_once('includes/setup.php');
	fejlec();
	
	$news = $db->getRows("SELECT * FROM news n INNER JOIN users u ON n.user_id=u.id WHERE n.public='1' ORDER BY n.date DESC");
	$db->disconnect();
	
	if(isset($_GET['Logout'])){
	session_destroy();
	echo '<script>window.location.href="index.php"</script>';
}
?>
	<a href="index.php">Hírek</a>
	<a href="addnews.php">Új hír</a>
	<?php if(!$_SESSION['user']){ ?>
		<a href="login.php">Bejelentkezés</a>
	<?php } else { ?>
		<a href="?Logout">Kijelentkezés</a>
	<?php } ?>
	<h2>Hírek</h2>
<?php
	if ($news) foreach($news as $hir) {
		$szerzo = ($hir['name']=='') ? $hir['email'] : $hir['name'];
		echo $hir['title'].' - '.$szerzo; ?>
		<p align="justify"><?php echo $hir['text']; ?></p>
		---<br /><br /> <?php
	}
	
	lablec();
?>