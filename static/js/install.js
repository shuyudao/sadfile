$('#start_install').click(function () {
	for(var i = 0 ; i < $('input').length ; i++){
		if($('input')[i].value==''){
			alert("请确保每一项都填写完整");
			return false;
		}	
	}
})