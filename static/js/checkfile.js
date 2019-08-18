// 根据文件名返回对应的文件图标
function checkFile(filename) {
    filename = filename.toLowerCase();
    var spl = filename.split(".");
    var suffix = spl[spl.length-1];

    var icon = "";
    if (suffix=="pdf") {
    	icon = '<i class="mdui-list-item-avatar mdui-icon material-icons mdui-color-red mdui-text-color-white">picture_as_pdf</i>';
    }else if (suffix=="txt"||suffix=="doc"||suffix=="docx"||suffix=="xls"||suffix=="xlsx"||suffix=="ppt"||suffix=="pptx") {
    	icon = '<i class="mdui-list-item-avatar mdui-icon material-icons mdui-color-blue-600 mdui-text-color-white">library_books</i>';
    }else if (suffix=="mp4"||suffix=="avi"||suffix=='flv'||suffix=="mkv"||suffix=="wmv") {
    	icon = '<i class="mdui-list-item-avatar mdui-icon material-icons mdui-color-light-blue-600 mdui-text-color-white">video_library</i>';
    }else if (suffix=="mp3"||suffix=="m4a"||suffix=="wav"||suffix=="flac"||suffix=="aac") {
    	icon = '<i class="mdui-list-item-avatar mdui-icon material-icons mdui-color-light-green-600 mdui-text-color-white">library_music</i>';
    }else if (suffix=="jpg"||suffix=="png"||suffix=="gif"||suffix=="jpeg"||suffix=="bmp") {
    	icon = '<i class="mdui-list-item-avatar mdui-icon material-icons mdui-color-orange-600 mdui-text-color-white">photo_library</i>';
    }else if (suffix=="zip"||suffix=="7z"||suffix=="rar"||suffix=="gz") {
    	icon = '<i class="mdui-list-item-avatar mdui-color-grey-200 iconfont icon-yasuobao"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-yasuobao"></use></svg></i>';	
    }else{
    	icon = '<i class="mdui-list-item-avatar mdui-icon material-icons mdui-color-grey-500 mdui-text-color-white">insert_drive_file</i>';
    }

    return icon;
}