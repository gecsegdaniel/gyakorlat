<?php
	session_start();
	include_once('includes/setup.php');
	
	if(isset($_POST['login'])){
		
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		
		$user = $db->getRow("SELECT * FROM users WHERE email = ".$db->escapeValue($email));
		if (($user['password'] == md5($_POST['password'].''.$user['salt'])) && ($user['active'] == '1')) {
			$_SESSION['user'] = $user;
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
<?php if(!$_SESSION['user']){ ?>
	<a href="login.php">Bejelentkezés</a>
<?php } else { ?>
	<a href="?Logout">Kijelentkezés</a>
<?php } ?>

<?php if(!$_SESSION['user']){ ?>
	<h2>Bejelentkezés</h2>
	<form method="post" action="">
	<p>
		<label for="email">E-mail:</label>
		<input type="text" name="email" />
	</p>
	<p>
		<label for="password">Jelszó:</label>
		<input type="password" name="password" />
	</p>
	<input name="login" type="submit" value="Belépés" />  - <a href="registration.php">Regisztráció</a>
	</form>
<?php 
	} else { ?>
	<p>Már be van jelentkezve <?php echo $_SESSION['user']['email']; ?> címmel. - <a href="?Logout">Kijelentkezés</a></p>
<?php
	}
	
	lablec();
?>