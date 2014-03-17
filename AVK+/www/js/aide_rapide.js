/*
VARIABLES
*/


//boutons
var init = document.getElementById("radio-ini");
var medic = document.getElementById("radio-warf");
var pSup = document.getElementById("pSup");

//divisions
var iniWarf = document.getElementById("iniWarf");
var iniFluo = document.getElementById("iniFluo");
var entWarf = document.getElementById("entWarf");
var entFluo = document.getElementById("entFluo");

//checkbox
var valve = document.getElementById("check-valve");

//affichage du traitement
var dose = 0;
var texte = "";
var affichage = document.getElementById("affichage");

//affichage erreurs
var divTxWImes = document.getElementById("divTxWImes");




//Affiche le bon formulaire suivant la demande utilisateur
function displayForm(){ 
	displayAffichage("");
	iniWarf.style.display = 'none';	
	iniFluo.style.display = 'none';	
	entWarf.style.display = 'none';	
	entFluo.style.display = 'none';	
	
	if(init.checked){
		if(medic.checked){
			iniWarf.style.display = '';	
		}
		else{
			iniFluo.style.display = '';	
		}
	}
	else{
		if(medic.checked){
			entWarf.style.display = '';	
		}
		else{
			entFluo.style.display = '';	
		}
	}
}


function displayAffichage(chaine){
	affichage.value = chaine;
}




//validation de l'Initiation Warfarine	
function result1(idTxWI, idj3sup){
	var j3sup = document.getElementById(idj3sup);
	var TxWI = document.getElementById(idTxWI);
	
	dose = 0;
	
	if(valve.checked){
		 // vérifier ce que ça change sur la dose ou l'INR
	}
	
	if(j3sup.checked){
		// J3 passé donc calcul en fonction de l'INR
		
		if (testTaux(TxWI) != ""){
			displayAffichage("INR incorrect");
			return false ;
		}
		TxWI = parseFloat(TxWI.value);
		if(pSup.checked){
			if(TxWI < 1.3){
				dose = 6;
			}
			else if(TxWI < 1.5){
				dose = 5;
			}
			else if(TxWI < 1.7){
				dose = 4;
			}
			else if(TxWI < 1.9){
				dose = 3;
			}
			else if(TxWI < 2.5){ 
				dose = 1;
			}
			else{
				//taux supérieur à 2.5
				displayAffichage("arrêt jusqu'à INR < 2.5 et reprendre à 1mg de Warfarine");
				return false;
			
			}
		
		}
		else{
			if (TxWI < 1.3){
				dose = 5;
			}	
			else if (TxWI < 1.5){
				dose = 4;
			}			
			else if (TxWI < 1.7){
				dose = 3;
			}		
			else if (TxWI < 1.9){
				dose = 2;
			}
			else if (TxWI < 2.5){
				dose = 1;
			}
			else{
				//taux supérieur à 2.5
				displayAffichage("arrêt jusqu'à INR < 2.5 et reprendre à 1mg de Warfarine");
				return false;
			
			}
			
		}
		
		texte = "Le traitement est de " + dose + "mg de warfarine";
	}
	else{
		//3 premiers jours de traitement
		if(pSup.checked){
			dose = 5;
		}
		else{
			dose = 4;
		}
		texte = "Pour les trois premiers jours de traitement le dosage est de " + dose + "mg de warfarine";
		
	}
	
	displayAffichage(texte);
	
}


//Validation de l'Initiation Fluindione
function result2(idTxFI, idD_FI, idj2sup, idFIJ2){
	var TxFI = document.getElementById(idTxFI);
	var D_FI = document.getElementById(idD_FI);
	var j2sup = document.getElementById(idj2sup);
	var FIJ2 = document.getElementById(idFIJ2);
	dose = 0;
	
		
	if (valve.checked){
		 // vérifier ce que ça change sur la dose ou l'INR
	}
	
	if (j2sup.checked){
		// J2 passé donc calcul en fonction de l'INR
		
		//test du taux
		
		if (testTaux(TxFI) != ""){
			displayAffichage("INR incorrect");
			return false;
		}
		
		
		TxFI = parseFloat(TxFI.value);
		
		if(FIJ2.checked){
			//traitement J2
			if (TxFI < 1.2){
				dose = 30;
			}
			else if (TxFI < 1.5){
				dose = 25;
			}
			else if (TxFI < 2){
				dose = 20;
			}
			else if (TxFI < 2.2){
				dose = 10;
			}
			else if(TxFI > 3){
				dose = 5;
			}
			else{
				dose = 20;
				//dose dans la norme pas de changement
			}
		}
		else{
			if (testDose(D_FI, "fluindione") != ""){
				displayAffichage("dose incorrecte");
				return false;
			}
			D_FI = parseFloat(D_FI.value);
			
			//traitement J4 ou supérieur
			if (TxFI < 1.6){
				dose = D_FI + 10;
			}
			else if (TxFI < 2){
				dose = D_FI + 5;
			}
			else if (TxFI < 2.5){
				dose = D_FI;
			}
			else if (TxFI < 3){
				if(D_FI > 20){
					dose = D_FI - 5;
				}
				else{
					dose = D_FI;
				}
			}
			else{
				//INR >= 3
				if(D_FI > 15){
					dose = D_FI - 10;
				}
				else{
					dose = D_FI;
				}
			}	
		}
		texte = "Le traitement est de " + dose + "mg de Fluindione";
	}
	else{
		//2 premiers jours de traitement
		texte = "Pour les deux premiers jours de traitement le dosage est d'un comprimé soit 20 mg de Fluindione";
	}
	
	displayAffichage(texte);
}


//Validation de l'Entretien Warfarine
function result3(idTxWE, idD_WE){
	var TxWE = document.getElementById(idTxWE);
	var D_WE = document.getElementById(idD_WE);
	dose = 0;
	//test si les cases son pleines, sinon afficher un message pour dire de remplir
	
	if(valve.checked){
		 // vérifier ce que ça change sur la dose ou l'INR
	}
	
	if (testTaux(TxWE) != ""){
			displayAffichage("INR incorrect");
			return false ;
	}
	TxWE = TxWE.value; 
	if (testDose(D_WE, "fluindione") != ""){
			displayAffichage("dose incorrecte");
			return false;
	}
	D_WE = parseInt(D_WE.value);
	
	if(TxWE > 4){
		if(TxWE < 6){
			displayAffichage("saut d'une prise, pas d'apport de vitamine K");
		}
		else if(TxWE < 10){
			displayAffichage("arrêt du traitement, 1 à 2 mg de vitamine K par voiz orale");
		}
		else{
			displayAffichage("arrêt du traitement jusqu'à INR < 2.5, 5 mg de vitamine K par voie orale")
		}
	}
	else{
		if(TxWE < 2.5){
			dose = D_WE;
			dose += 1;
		}
		else{
			dose = (D_WE)-1;
		}
		displayAffichage("Le traitement est de " + dose + "mg de warfarine");
	}
	
}


//Validation de l'Entretien Fluindione
function result4(idTxFE, idD_FE){
	var TxFE = document.getElementById(idTxFE);
	var D_FE = document.getElementById(idD_FE);
	
	if(valve.checked){
		 // vérifier ce que ça change sur la dose ou l'INR
	}
	if (testTaux(TxFE) != ""){
		displayAffichage("INR incorrect");
		return false ;
	}
	TxFE = TxFE.value;
	if (testDose(D_FE, "fluindione") != ""){
		displayAffichage("dose incorrecte");
		return false;
	}
	D_FE = parseInt(D_FE.value);
	
	if(TxFE > 4){
		if(TxFE < 6){
			displayAffichage("Saut d'une prise, pas d'apport de vitamine K, préléver l'INR le lendemain. diminuer la dose de 20%. reprise du traitement si INR < 2.5");
		}
		else if(TxFE < 10){
			displayAffichage("Arrêt du traitement, 2mg de vitamine K par voie orale, prélever l'INR le lendemain, diminuer la dose de 10mg reprise tu traitement si INR < 2.5");
		}
		else{
		// taux > 10
			displayAffichage("Arrêt du traitement, 5mg de vitamine k par voie orale, prélever l'INR le lendemain, changer d'AVK et préférer la WARAFARINE");
		}
	}
	else{
		//taux dans la norme
		if(TxFE < 2.5){
			dose = D_FE + ((20*D_FE)/100);
		}
		else{
			dose = D_FE - ((20*D_FE)/100);
		}
		displayAffichage("Le traitement est de " + dose + "mg de Fluindione");

	}
}



//Tests et affichage pour les inputs demandant de rentrer l'INR
function tauxChange(input, div, message){
	div = document.getElementById(div);
	message = document.getElementById(message);
	
	div.style.display = 'none';
	if(input.value == ""){
		input.style.backgroundColor="";
	}
	else{
		var chaine = testTaux(input);
		if (chaine == ""){
			input.style.backgroundColor="green";
		}
		else{
			input.style.backgroundColor="#fba";
			message.innerHTML = chaine;
			div.style.display = '';
		}
	}
}

function testTaux(input){
	var taux = input.value;
	
	if(input.value == ""){
		//champs non remplis
		return;
	}
	//cas pas de nombres
	if(isNaN(taux) || taux.charAt(0) == " " || taux.charAt(taux.length-1) == " "){
		return "l'INR est un taux et s'exprime uniquement en caractères numériques [0-9]";
	}
	taux = parseFloat(taux);
	//cas en dehors des bornes
	if (taux < 1 || taux > 15){
		return "l'INR doit être compris entre 1 et 15";
	}
	
	return "";
}


//Tests et affichages pour les inputs demandant de rentrer une dose médicamenteuse
function doseChange(input, div, message){
	div = document.getElementById(div);
	message = document.getElementById(message);
	
	div.style.display = 'none';
	if(input.value == ""){
		input.style.backgroundColor="";
	}
	else{
		var chaine = testDose(input);
		if (chaine == ""){
			input.style.backgroundColor="green";
		}
		else{
			input.style.backgroundColor="#fba";
			message.innerHTML = chaine;
			div.style.display = '';
		}
	}
}

function testDose(input, type){
	var dose = input.value;
	var ret = "";
	
	if(dose == ""){
		return;
	}
	
	if(isNaN(dose) || dose.charAt(0) == " " || dose.charAt(dose.length-1) == " "){
		return "la dose s'exprime uniquement en caractères numériques [0-9]";
	}
	//cas en dehors des bornes
	if(type == "warfarine"){
		//bornes pour la warfarine
		if(dose < 1 || dose > 50){
			return "la dose doit être comprise entre 1 et 50 pour la warfarine";
		}
	}
	else{
		//bornes pour la fluindione
		if(dose < 1 || dose > 50){
			return "la dose doit être comprise entre 1 et 50 pour la fluindione";
		}
	}
	return "";
}



//Affiche ou non un element en fonction d'une case cochée
function displayTaux(bouton, champ){
	if(bouton.checked){
		document.getElementById(champ).style.display = '';
	}
	else{
		document.getElementById(champ).style.display = 'none';
	}
}



var theList = document.getElementById('theList');
var result = document.getElementById('result');
var save = theList.innerHTML;

function recherche(theSearch){
	result.innerHTML = "<h2>"+theSearch.id+"</h2>";
	theList.innerHTML = save;
	switch(theSearch.id){
		case "Allopurinol":
			//alert("Allopurinol :\n Augmente l'INR jusqu'à 8 jours après l'arrêt.");
			result.innerHTML += "<img src=\"../../img/Augmentation_8jours.png\"><br>Augmente l'INR jusqu'à 8 jours après l'arrêt.";
		break;
		case "Androgènes":
			//alert("Androgènes :\n Augmente l'INR jusqu'à 8 jours après l'arrêt.");
			result.innerHTML += "<img src=\"../../img/Augmentation_8jours.png\"><br>Augmente l'INR jusqu'à 8 jours après l'arrêt.";
		break;
		case "ISRS":
			//alert("ISRS :\n Augmente l'INR.");
			result.innerHTML += "<img src=\"../../img/Augmentation.png\"><br>Augmente l'INR.";
		break;
		case "Benzbromaron":
			//alert("Benzbromaron :\n Augmente l'INR.");
			result.innerHTML += "<img src=\"../../img/Augmentation.png\"><br>Augmente l'INR.";
		break;
		case "Aprepitants":
			//alert("Aprepitants :\n Augmente l'INR jusqu'à 8 jours après l'arrêt.");
			result.innerHTML += "<img src=\"../../img/Augmentation_8jours.png\"><br>Augmente l'INR jusqu'à 8 jours après l'arrêt.";
		break;
		case "Aminoglutethimide":
			//alert("Aminoglutethimide :\n Diminuel'INR jusqu'à 2 semaines après l'arrêt.");
			result.innerHTML += "<img src=\"../../img/Diminution_2semaines.png\"><br>Diminue l'INR jusqu'à 2 semaines après l'arrêt.";
		default:
	}
	
}

