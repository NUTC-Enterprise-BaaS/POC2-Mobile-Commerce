function imageUploaderDropzone(dropZoneDiv,url,clickableButtons,MultiLanguageMessage,ImagePath,paralelUploadNumber,pictureAdder) {
    Dropzone.autoDiscover = false;
    jQuery(dropZoneDiv).dropzone({
        url: url,
        addRemoveLinks: true,
        acceptedFiles:'image/gif,.jpg,.jpeg,.png',
        maxFilesize: 10, // MB
        enqueueForUpload: true,
        dictRemoveFile: "移除預覽",
        autoProcessQueue: false,
        parallelUploads: paralelUploadNumber,
        dictDefaultMessage: MultiLanguageMessage,
        clickable: clickableButtons,


        // The setting up of the dropzone
        init: function () {
            var myDropzone = this;
            jQuery("#submitAll").click(function (e) {
                e.preventDefault();
                e.stopPropagation();
                myDropzone.processQueue();
                jQuery('button').each(function () {
                    jQuery(this).remove('#add');
                });
            });
            this.on("addedfile", function (file) {
                var addButton = Dropzone.createElement("<button id='add' class='btn btn-primary start' >預覽</button>");
                addButton.addEventListener("click", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    myDropzone.processFile(file);
                    file.previewElement.classList.add("dz-success");
                    jQuery(this).remove();
                });
                file.previewElement.appendChild(addButton);
            });
        },
        success: function (file, response) {
            var imgName = response;
            var name;
            name = file.name.replace(/[^0-9a-zA-Z.]/g,'');
            file.previewElement.classList.add("dz-success");
            switch (pictureAdder){
                case "addPicture":
                    addPicture(ImagePath + name, name);
                    break;
                case "setUpLogo":
                    setUpLogo(name);
                    break;
                case "setUpLogoExtraOptions":
                    setUpLogoExtraOptions(ImagePath + name,name);
                    break;
                default :
                    alert("錯誤! 無圖片檢視");
                    console.log("無圖片可定義");
                    break;
            }
        },
        error: function (file, response) {
            file.previewElement.classList.add("dz-error");
            console.log(response);
        }
    });
}

function photosNameFormater(imageName){
    var NameLength = imageName.length;
    if(NameLength > 14){
        return  imageName.substring(imageName.length - 14);
    }else{
        return imageName;
    }
}