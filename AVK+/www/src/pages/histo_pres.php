<?php 

include 'dbc.php';

if($_POST['doRegister'] == 'Register') {
	foreach($_POST as $key => $value) {
		$data[$key] = filter($value);
	}
	
	echo lol;
	$sql_inser = "INSERT INTO `prescription` (`Medicament`, `Dose`, `INR`, `Date`)
	VALUES ('$data[pres_med]', '$data[pres_dose]', '$data[pres_INR]', now())";
	mysql_query($sql_inser) or die("Insert failed: " . mysql_error());
	$prescription_id = mysql_insert_id($link);
	
	// Mise a jour - recuperation de l'id patient depuis son nom
	// Jointure entre users et patient (a finir)
	//$select = "SELECT idPatient FROM patient WHERE Nom = '$data[user_cabinet]'";
	/*$rq = mysql_query($sql_select) or die(mysql_error());
	$tab = mysql_fetch_array($rs);
	$update = "UPDATE patient SET HistoINR_idHistoINR = '$histoinr_id', WHERE idPatient = '$tab[idPatient]'";
	// histoinr
	$update = "UPDATE patient SET HistoINR_idHistoINR = '$histoinr_id' WHERE idPatient = '$tab[idPatient]'";*/

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
	<link rel="stylesheet" href="../../css/calendrier.css" />
	<script src="../../js/jquery-1.10.2.min.js"></script>
	<script src="../../js/jquery.mobile-1.4.0.min.js"></script>
	<script src="../../js/jquery-1.3.2.min.js"></script>
	<script src="../../js/jquery.validate.js"></script>
	<script src="../../js/register.js"></script>
	<script type="text/javascript" src="../../js/calendrier.js"></script>
	<script type="text/javascript" src="../../js/aide_rapide.js"></script>
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
		
			<h1>Créer une nouvelle prescription</h1>
			<tr> 
				<td colspan="2">&nbsp;</td>
			  </tr>
			<form action="register.php" method="post" name="regForm" id="regForm" >
			<table width="55%" border="0" cellpadding="3" cellspacing="3" class="forms">
			  <tr> 
				<td colspan="2">Date<span ><font color="#CC0000">*</font></span> 
				  <input type="text" name="pres_date" id="pres_date" class="calendrier" size="8" />
				  </td>
			  </tr>
			  <tr> 
				<td colspan="2">&nbsp;</td>
			  </tr>
			  <tr> 
				<td colspan="2"><h4><strong>Détails</strong></h4></td>
			  </tr>
			  <tr> 
				<tr> 
				<td>Médicament<span class="required"><font color="#CC0000">*</font></span>
				</td>
				<td>
				<SELECT name="pres_med" id="pres_med" onChange="affichage(this.value);">
					<OPTION value="warfarine">Warfarine </OPTION>
					<OPTION value="coumadine">Coumadine</OPTION>
				</SELECT>
				</tr>
				<tr>
					<form name="" >
						<label for="champTx">INR : </label>
							<input type="text" name="pres_INR" id="TxWE" onKeyUp="tauxChange(this, 'divTxWEmes', 'TxWE_mes');" >
							<div id="divTxWEmes" style="display:none;" >
							<p name="Tx_war_ent_message" id="TxWE_mes" style="color:red;"></p>
							</div>
						
						<label for="champDose">Dose (mg) : </label>
							<input type="text" name="pres_dose" id="D_WE" onKeyUp="doseChange(this, 'divDWEmes', 'DWE_mes');" >
							<div id="divDWEmes" style="display:none;" >
							<p name="D_war_ent_message" id="DWE_mes" style="color:red;"></p>
							</div>
					</form>
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
