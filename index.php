<?php
session_start();
require_once('session/class.user.php');
$user = new USER();

if($user->is_loggedin()!="")
{
	$user->redirect('index.php');
}

if(isset($_POST['btn-signup']))
{
	$uname = strip_tags($_POST['txt_uname']);
	$umail = strip_tags($_POST['txt_umail']);
	$upass = strip_tags($_POST['txt_upass']);	
	
	if($uname=="")	{
		$error[] = "Veuillez rentrer un pseudo !";	
	}
	else if($umail=="")	{
		$error[] = "Veuillez rentrer un email !";	
	}
	else if(!filter_var($umail, FILTER_VALIDATE_EMAIL))	{
	    $error[] = 'Ajoutez une adresse mail valide !';
	}
	else if($upass=="")	{
		$error[] = "Veuillez rentrer un mot de passe !";
	}
	else if(mb_strlen($upass) < 6){
		$error[] = "Le mot de passe doit avoir 6 characters";	
	}
	else
	{
		try
		{
			$stmt = $user->runQuery("SELECT name, email FROM users WHERE name=:uname OR email=:umail");
			$stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
				
			if($row['name']==$uname) {
				$error[] = "Désole ! Ce pseudo est non valide ou déjà utilisé.";
			}
			else if($row['email']==$umail) {
				$error[] = "Désole ! Cet e-mail est non valide ou déjà utilisé.";
			}
			else
			{
				if($user->register($uname,$umail,$upass)){	
					$user->redirect('index.php?joined');
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}	
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Grim Reaper</title>
	<link rel="stylesheet" href="assets/reset.css">
	<link rel="stylesheet" href="assets/styles.css">
	<link rel="stylesheet" href="assets/animate.css">
	<link href='https://fonts.googleapis.com/css?family=Lato:400,700,900,300' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Wendy+One' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Kalam:400,700' rel='stylesheet' type='text/css'>
</head>
<body>
		<img class="imgparax" src="img/roche_fond.png" alt="fond" id="roche3">
        <img class="imgparax" src="img/fumee.png" alt="fumee" id="fumee">
        <img class="imgparax" src="img/roche1.png" alt="premier" id="roche1">
        <img class="imgparax" src="img/back.jpg" alt="rouge">

	<div class="content">
		<header>
			<div class="prout"><a href="#"><p>Grim Reaper</p></a></div>
			<nav class="first-nav">
				<ul>
					<a href="jeu/Build.html" class="js-scrollTo"><li>jeu</li></a>
					<a href="#sectionGameplay" class="js-scrollTo"><li>règles</li></a>
					<a href="session/index.php" class="js-scrollTo"><li>se connecter</li></a>
					<a href="#sectionInscription" class="js-scrollTo"><li>inscription</li></a>
				</ul>
				<div class="clear"></div>
			</nav>

			<div class="titre">
				<h1>Grim Reaper</h1>

				<!-- picto Play -->
				<a href="jeu/Build.html"><img src="img/play.png" alt="play" class="play animated bounceIn"></a><br>
				<a href="#"><img src="img/jouer.png" alt="jouer" class="jouer"></a>
			</div>
			<!-- /titre -->
		</header>

		<section class="inscription" id="sectionInscription">
			<p>«Une jeune fille est à la recherche de la faucheuse pour ramener sa mère à la vie.<br>
				Pour cela, elle doit affronter ses démons dans le monde des morts.»</p>
			<h2>inscription</h2>

			<!-- <div class="formulaire">
				<form action="" class="apply">
					<input type="text" placeholder="Pseudo">
					<input type="text" placeholder="Email">
					<input type="password" placeholder="Mot de passe">
					<input type="submit" value="VALIDER" class="voila">
				</form>
				<a href="#" class="viafb">Je préfère m'inscrire via Facebook</a>
			</div> -->

			<div class="container">	
        <form method="post" class="form-signin" style="float: left;">
            <?php
			if(isset($error))
			{
			 	foreach($error as $error)
			 	{
					 ?>
                     <div class="alert alert-danger">
                        <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                     </div>
                     <?php
				}
			}
			else if(isset($_GET['joined']))
			{
				 ?>
                 <div class="alert alert-info">
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Inscription Reussite <a href='index.php'>Connectez-vous ici</a>
                 </div>
                 <?php
			}
			?>
            <div class="form-group">
            <input type="text" id="nom" class="form-control" name="txt_uname" placeholder="Pseudo" value="<?php if(isset($error)){echo $uname;}?>" />
            </div>
            <div class="form-group">
            <input type="text" id="email" class="form-control" name="txt_umail" placeholder="E-mail" value="<?php if(isset($error)){echo $umail;}?>" />
            </div>
            <div class="form-group">
            	<input type="password" id="password" class="form-control" name="txt_upass" placeholder="Mot de passe" />
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
            	<button type="submit" class="btn btn-primary" name="btn-signup">
                	<i class="glyphicon glyphicon-open-file"></i>&nbsp;Valider
                </button>
            </div>
            <br />
            <label>Vous avez un compte ? <a href="index.php">Connectez-vous</a></label>
        </form>
       </div>
			<!-- /formulaire -->
			<img src="img/manga.png" alt="manga" class="manga">
			<div class="clear"></div>
		</section>

		<section class="niveaux" id="sectionNiveaux">
			<h2>les niveaux</h2>
			<h4>Bonne nouvelle : tu peux déjà jouer au premier niveau</h4>
			<img src="img/lvl1.png" alt="level 1">

			<h4>Inscris-toi pour débloquer les niveaux suivants !<br><br>
				Ensuite, tu seras notifié d'un nouvel épisode chaque semaine.</h4>
			<div class="levels">
				<img src="img/lvl2.png" alt="level 2">
				<img src="img/lvl3.png" alt="level 3">
				<img src="img/lvl4.png" alt="level 4">
			</div>
			<!-- /levels -->
		</section>
		<!-- /inscription -->
		
		<section class="gameplay" id="sectionGameplay">
			<h2>gameplay</h2>
			<div class="manette">
				<img src="img/gameplay.png" alt="gameplay" class="sauter">
			</div>
			<!-- /manette -->
		</section>
		<!-- /gameplay -->

	</div>

	<footer>
		<img src="img/footer.png" alt="footer" class="footer">
		<!-- /social -->
		<a href="https://www.facebook.com/Grim-Reaper-Game-699147933521239/" target="_blank" alt="Page Facebook">
			<div class="facebook"></div>
		</a>
		
		<a href="https://twitter.com/grimreaprergame" target="_blank">
			<div class="twitter"></div>
		</a>
		
	</footer>

	<!-- /content -->
	<a href="#"><img src="img/arrow.png" alt="up" class="toTop" title="Retour en haut"/></a>

        <script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
        <script src="main.js"></script>
</body>
</html>