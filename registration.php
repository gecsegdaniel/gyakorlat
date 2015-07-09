<?php
	session_start();
	include_once('includes/setup.php');
	
	if(isset($_POST['registration'])){
		
		if(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) !== false && isset($_POST['password']) && $_POST['password'] != ''){
			$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
			
			$user = $db->getRow("SELECT * FROM users WHERE email = ".$db->escapeValue($email)); // lekérdezzük a beírt e-mail címet, van-e ilyen már a DBben.
			if(!$user) { //ha nincs még ezzel az e-mail címmel reg.-elve senki
				
				$salt = rand(10000,99999); // generálunk egy salt-ot a usernek.
				$password = md5($_POST['password'].''.$salt); // md5-öljük a beírt jelszót, plusz a generált számot.
				
				$db->insert('users',array('email' => $email,'password' => $password,'salt' => $salt,'active' => '1'));
				echo '<script>window.location.href="login.php"</script>';
			}
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
	<h2>Regisztráció</h2>
	<form method="post" action="">
	<p>
		<label for="email">E-mail:</label>
		<input type="text" name="email" />
	</p>
	<p>
		<label for="password">Jelszó:</label>
		<input type="password" name="password" />
	</p>
	<input name="registration" type="submit" value="Regisztráció" />
	</form>
<?php 
	} else { ?>
	<p>Már be van jelentkezve <?php echo $_SESSION['user']['email']; ?> címmel. - <a href="?Logout">Kijelentkezés</a></p>
<?php
	}
	
	lablec();
?>