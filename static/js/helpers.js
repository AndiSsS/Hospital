function formControl(form, isDelete){
	if(isDelete==true){
		if(confirm("Ви впевнені?")==true){
			form.submit();
		}
	}
	else{
		for(var i=0; i < form.elements.length; i++){
		    if(form.elements[i].value === '' && form.elements[i].hasAttribute('required')){
		        alert('Заповніть всі поля!');
		        return false;
		    }
		}
		form.submit();
	}
}; 
function rowsPerPage(count){
	document.cookie = "rows_per_page="+count
	window.location = window.location.href.split("?")[0];
}