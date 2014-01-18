function display1Div(div1, div2){
	var divV = document.getElementById(div1);
	var divF = document.getElementById(div2);
	if(divV.style.display != ""){
		divF.style.display = "none";
		divV.style.display = "";
	}
}

function displayMedic(bouton){
	if(bouton.id == 'radio-choice-1'){
		document.getElementById("fluindione1").style.display = 'none';
		document.getElementById("fluindione2").style.display = 'none';
		document.getElementById("fluindione3").style.display = 'none';
		document.getElementById("fluindione4").style.display = 'none';

		document.getElementById("warfarine1").style.display = '';
		document.getElementById("warfarine2").style.display = '';
		document.getElementById("warfarine3").style.display = '';
		document.getElementById("warfarine4").style.display = '';
	}
	else{
		document.getElementById("warfarine1").style.display = 'none';
		document.getElementById("warfarine2").style.display = 'none';
		document.getElementById("warfarine3").style.display = 'none';
		document.getElementById("warfarine4").style.display = 'none';
		
		document.getElementById("fluindione1").style.display = '';
		document.getElementById("fluindione2").style.display = '';
		document.getElementById("fluindione3").style.display = '';
		document.getElementById("fluindione4").style.display = '';
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

