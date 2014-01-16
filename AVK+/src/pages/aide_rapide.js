function displayForm(courant, autre){
	if(courant.style.display != ''){
		autre.style.display = 'none';
		courant.style.display = '';
	}
}