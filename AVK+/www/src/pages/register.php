<?php 

include 'dbc.php';

if($_POST['doRegister'] == 'Register') {
	foreach($_POST as $key => $value) {
		$data[$key] = filter($value);
	}

	require_once('recaptchalib.php');
     
      $resp = recaptcha_check_answer ($privatekey,
                                      $_SERVER["REMOTE_ADDR"],
                                      $_POST["recaptcha_challenge_field"],
                                      $_POST["recaptcha_response_field"]);

      if (!$resp->is_valid) {
        die ("<h3>Image Verification failed!. Go back and try again.</h3>" .
             "(reCAPTCHA said: " . $resp->error . ")");			
      }
	  
	if(empty($data['full_name']) || strlen($data['full_name']) < 4) {
		$err[] = "ERROR - Invalid name. Please enter atleast 3 or more characters for your name";
	}
	
	if (!isUserID($data['user_name'])) {
		$err[] = "ERROR - Invalid user name. It can contain alphabet, number and underscore.";
	}
	
	if(!isEmail($data['usr_email'])) {
		$err[] = "ERROR - Invalid email address.";
	}

	if (!checkPwd($data['pwd'],$data['pwd2'])) {
		$err[] = "ERROR - Invalid Password or mismatch. Enter 5 chars or more";
	}
	
	$sha1pass = PwdHash($data['pwd']);

	// Automatically collects the hostname or domain  like example.com) 
	$host  = $_SERVER['HTTP_HOST'];
	$host_upper = strtoupper($host);
	$path   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

	// Generates activation code simple 4 digit number
	$activ_code = rand(1000,9999);

	$usr_email = $data['usr_email'];
	$user_name = $data['user_name'];
	 
	$rs_duplicate = mysql_query("select count(*) as total from users where user_email='$usr_email' OR user_name='$user_name'") or die(mysql_error());
	list($total) = mysql_fetch_row($rs_duplicate);	

	if ($total > 0) {
		$err[] = "ERROR - The username/email already exists. Please try again with different username and email.";
	}
	
	if(empty($err)) {
		$sql_insert = "INSERT into `users`
  			(`full_name`,`user_name`, `user_email`,`pwd`,`Adresse`,`N Tel`,`date`,`activation_code`, `Cabinet_idCabinet`)
		    VALUES
		    ('$data[full_name]','$user_name', '$usr_email','$sha1pass','$data[address]','$data[tel]',now(), '$activ_code', 1)
			";
		mysql_query($sql_insert,$link) or die("Insertion Failed:" . mysql_error());
		$user_id = mysql_insert_id($link); 	
		$md5_id = md5($user_id);
		mysql_query("update users set md5_id='$md5_id' where id='$user_id'");	
		
		// Mise a jour de l'ID du cabinet dans la table users
		$sql_select = "SELECT idCabinet FROM cabinet WHERE Nom = '$data[user_cabinet]'";
		$rs = mysql_query($sql_select) or die(mysql_error());
		$tab = mysql_fetch_array($rs);
		mysql_query("UPDATE users SET Cabinet_idCabinet = '$tab[idCabinet]' WHERE id='$user_id'");	
	
		// Mise a jour du user_level dans la table users		
		if ($data[user_fonction] == "responsable") {
			echo responsable;
			$sql_insResp = "UPDATE users SET user_level='3' WHERE id='$user_id'";
			mysql_query($sql_insResp,$link) or die("Update Failed:" . mysql_error());
		}
		
		else if ($data[user_fonction] == "personnel") {
			echo personnel;
			$sql_insPers = "UPDATE users SET user_level='2' WHERE id= '$user_id'";
			mysql_query($sql_insPers) or die("Update Failed:" . mysql_error());
		}
		
		else {
			echo patient;
			$sql_insPat = "UPDATE users SET user_level='1' WHERE id='$user_id'";
			mysql_query($sql_insPat,$link) or die("Update Failed:" . mysql_error());
			$sql_ins = "INSERT INTO `patient` (`Age`, `Poids`, `N°Secu`) VALUES ('$data[patient_age]', '$data[patient_poids]', '$date[patient_nsecu]')";
			mysql_query($sql_ins) or die("Insert failed: " . mysql_error());
			$patient_id = mysql_insert_id($link);
	
			// Demandez s'il veut rentrer la prescription et l'historique tout de suite
			// Si 'non' : patient.histoinr = null et patient.prescription = null
			// Si 'oui' : suivre la démarche en dessous
			// Rajouter un bouton
			//header("Location: histo_pres.php");
			//exit();
		}	
	}
  //header("Location: thankyou.php");  
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AVK+</title>
	<link rel="stylesheet"  href="../../css/jquery.mobile.icons-1.4.0.min.css">
	<link rel="stylesheet" href="../../css/themes/default/ThemeBleu.min.css" />
	<link rel="stylesheet" href="../../css/jquery.mobile.structure-1.4.0.min.css" />
	<script src="../../js/jquery-1.10.2.min.js"></script>
	<script src="../../js/jquery.mobile-1.4.0.min.js"></script>
	<script src="../../js/jquery-1.3.2.min.js"></script>
	<script src="../../js/jquery.validate.js"></script>
	<script src="../../js/register.js"></script>
	<style>
		.content-primary { text-align:center;}
		.userform { padding:.8em 1.2em; }
		.userform h2 { color:#555; margin:0.3em 0 .8em 0; padding-bottom:.5em; border-bottom:1px solid rgba(0,0,0,.1); text-align: center;
		.userform label { display:block; margin-top:1.2em; }
		.switch .ui-slider-switch { width: 6.5em !important }
		.ui-grid-a { margin-top:1em; padding-top:.8em; margin-top:1.4em; border-top:1px solid rgba(0,0,0,.1); }
		
    </style>
</head>
<body>

	<div data-role="page">
		<div data-role="header" data-theme="a">
			<h1>AVK+</h1>
			<a href="#left-panel" data-icon="arrow-l">Menu</a>
			
		</div>
		<!-- panneau  de gauche (menu) -->
		<div data-role="panel" data-position="left" data-position-fixed="false" data-display="overlay" id="left-panel" data-theme="a">
			<form class="userform">
				<h2>Menu</h2>
				<a href="../../index.html" data-ajax="false" data-icon="home" data-role="button" data-theme="c" >Accueil</a>
				<a href="aide_rapide.html" data-ajax="false" data-icon="gear" data-role="button" data-theme="c" >Aide rapide</a>
				<a href="protocole.html" data-ajax="false" data-icon="info" data-role="button" data-theme="c" >Le protocole AVK</a>
				<a href="historique.html" data-ajax="false" data-icon="grid" data-role="button" data-theme="c" >Dossiers patients</a>
				<h2>Recherche</h2>
				<ul id="List" data-role="listview" data-icon="false" data-filter="true" data-filter-placeholder="Rechercher" data-filter-reveal="true" data-theme="a">
				
			</form>
		</div>
		<div data-role="content">
		
			<h1>Créer un nouveau compte</h1>
			<tr> 
				<td colspan="2">&nbsp;</td>
			  </tr>
			<form action="register.php" method="post" name="regForm" id="regForm" >
			<table width="95%" border="0" cellpadding="3" cellspacing="3" class="forms">
			  <tr> 
				<td colspan="2">Votre nom complet (Nom-Prénom)<span class="required"><font color="#CC0000">*</font></span><br> 
				  <input name="full_name" type="text" id="full_name" size="40" class="required"></td>
			  </tr>
			  <tr> 
				<td colspan="2">&nbsp;</td>
			  </tr>
			  <tr> 
				<td colspan="2">Adresse<span class="required"><font color="#CC0000">*</font></span><br> 
				  <textarea name="address" cols="40" rows="4" id="address" class="required"></textarea> 
				  <span class="example">ENTREZ UNE ADRESSE VALIDE</span> </td>
				<tr> 
				<td colspan="2">&nbsp;</td>
			  </tr>
				<td>Téléphone<span class="required"><font color="#CC0000">*</font></span> 
				</td>
				<td><input name="tel" type="text" id="tel" class="required"></td>
			  </tr>
			  <tr> 
				<td colspan="2">&nbsp;</td>
			  </tr>
			  <tr> 
				<td colspan="2"><h4><strong>Détails du compte</strong></h4></td>
			  </tr>
			  <tr> 
				<tr> 
				<td>Fonction<span class="required"><font color="#CC0000">*</font></span>
				</td>
				<td>
				<SELECT name="user_fonction" id="user_fonction" onChange="affichage(this.value);">
					<OPTION value="responsable">Responsable de cabinet </OPTION>
					<OPTION value="personnel">Personnel soignant</OPTION>
					<OPTION value="patient">Patient autogéré</OPTION>
				</SELECT>
				</tr>
				<tr> 
				<td>Cabinet<span class="required"><font color="#CC0000">*</font></span> 
				</td>
				<td>
				<?php
				$sql = "select * from cabinet;";
				$rs_results = mysql_query($sql) or die(mysql_error());
				?>
				<SELECT name="user_cabinet" id="user_cabinet">
				<?php while ($rrows = mysql_fetch_array($rs_results)) {?>
				<OPTION VALUE="<?php echo $rrows['Nom']; ?>"><?php echo $rrows['Nom']; ?></OPTION>
				<?php }?>
				</SELECT>
				</SELECT>
				</tr>
				<td>Pseudo de connexion<span class="required"><font color="#CC0000">*</font></span></td>
				<td><input name="user_name" type="text" id="user_name" class="required username" minlength="5" > 
				  <input name="btnAvailable" type="button" id="btnAvailable" 
				  onclick='$("#checkid").html("Please wait..."); $.get("checkuser.php",{ cmd: "check", user: $("#user_name").val() } ,function(data){  $("#checkid").html(data); });'
				  value="Check Availability"> 
					<span style="color:red; font: bold 12px verdana; " id="checkid" ></span> 
				</td>
			  </tr>
			  <tr> 
				<td>Courriel<span class="required"><font color="#CC0000">*</font></span> 
				</td>
				<td><input name="usr_email" type="text" id="usr_email3" class="required email"> 
				  <span class="example">** Adresse valide..</span></td>
			  </tr>
			  <tr> 
				<td>Mot de passe<span class="required"><font color="#CC0000">*</font></span> 
				</td>
				<td><input name="pwd" type="password" class="required password" minlength="5" id="pwd"> 
				  <span class="example">** 5 caractères minimums..</span></td>
			  </tr>
			  <tr> 
				<td>Retapez votre mot de passe<span class="required"><font color="#CC0000">*</font></span> 
				</td>
				<td><input name="pwd2"  id="pwd2" class="required password" type="password" minlength="5" equalto="#pwd"></td>
			  </tr>
			  <tr> 
				<td colspan="2">&nbsp;</td>
			  </tr>
			  <tr> 
				<td colspan="2"><h4><strong>Si vous êtes un patient autogéré</strong></h4></td>
			  </tr>
			  <div id="div_patient">
					<tr> 
					<td>Numéro de sécurité sociale<span class="required"><font color="#CC0000">*</font></span> 
					</td>
					<td><input name="patient_nsecu" type="text" id="patient_nsecu" class="required"></td>
				  </tr>
				  <tr> 
					<td>Age<span class="required"><font color="#CC0000">*</font></span> 
					</td>
					<td><input name="patient_age" type="text" id="patient_age" class="required"></td>
				  </tr>
				  <tr> 
					<td>Poids en Kg<span class="required"><font color="#CC0000">*</font></span> 
					</td>
					<td><input name="patient_poids" type="text" id="patient_poids" class="required"></td>
				  </tr>
			  </div>
			  <tr> 
				<td colspan="2">&nbsp;</td>
			  </tr>
			  <tr> 
				<td width="22%"><strong>Captcha </strong></td>
				<td width="78%"> 
				  <?php 
				require_once('recaptchalib.php');
				
					echo recaptcha_get_html($publickey);
				?>
				</td>
			  </tr>
			</table>
			<p align="center">
			  <input name="doRegister" type="submit" id="doRegister" value="Register">
			</p>
		  </form>
		</div>
		
		<div data-role="footer" data-theme="a">
			<h1>AVK+ Groupe C</h1>
		</div>
		
	</div>
</body>
</html>