/**
	Check upload photo
	Avoid Vulnerable
	@element_id: Id of the file type tag
**/
function checkUploadPhotoFiles(element_id){
	var element = document.getElementById(element_id);
	var photo_name = element.value.toUpperCase();
    suffix=".JPG";
    if(!(photo_name.indexOf(suffix, photo_name.length - suffix.length) !== -1)){
    	alert('Alow file: *.jpg');
        element.value='';
    }
}