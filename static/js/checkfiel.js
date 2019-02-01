
	var files = document.getElementById('files').getElementsByTagName('tr');
	var filename = document.getElementById('files').getElementsByTagName('tr');
	for(var i = 0 ; i < files.length ; i++){
		var files2 = files[i].getElementsByTagName('td')[0].getElementsByTagName('i')[0];
		var filename2 = filename[i].getElementsByTagName('td')[0].getElementsByTagName('em')[0];
		if (filename2.innerHTML.lastIndexOf('.zip')!=-1) {
			files2.className = "iconfont icon-yasuobao";
		}else if(filename2.innerHTML.lastIndexOf('.mp4')!=-1){
			files2.className = "iconfont icon-shipin";
		}else if (filename2.innerHTML.lastIndexOf('.docx')!=-1||filename2.innerHTML.lastIndexOf('.doc')!=-1) {
			files2.className = "iconfont icon-wendang";
		}else if (filename2.innerHTML.lastIndexOf('.php')!=-1||filename2.innerHTML.lastIndexOf('.html')!=-1|filename2.innerHTML.lastIndexOf('.java')!=-1) {
			files2.className = "iconfont icon-html";
		}else if (filename2.innerHTML.lastIndexOf('.')==-1) {
			files2.className = "iconfont icon-wenjian";
		}else if (filename2.innerHTML.lastIndexOf('.jpg')!=-1||filename2.innerHTML.lastIndexOf('.jpeg')!=-1||filename2.innerHTML.lastIndexOf('.png')!=-1||filename2.innerHTML.lastIndexOf('.gif')!=-1) {
			files2.className = "iconfont icon-picture_icon";
		}else{
			files2.className = "iconfont icon-wenjian1";
		}
	}
