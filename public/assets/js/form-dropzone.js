Dropzone.autoDiscover = false;
var acceptedFileTypes = "image/*"; //dropzone requires this param be a comma separated list
var fileList = new Array;
var i = 0;
$("#my-awesome-dropzone").dropzone({
    addRemoveLinks: true,
    maxFiles: 10, //change limit as per your requirements
    dictMaxFilesExceeded: "Maximum upload limit reached",
    acceptedFiles: acceptedFileTypes,
    dictInvalidFileType: "upload only JPG/PNG",
    init: function () {

        this.on("accept", function (file) {

            alert("asss");
             var re = /(?:\.([^.]+))?$/;
            var ext = re.exec(file.name)[1];
            ext = ext.toUpperCase();
            if ( ext == "JPG" || ext == "JPEG" || ext == "PNG" ||  ext == "GIF" ||  ext == "BMP") 
            {
                done();
            }else { 
                done("Please select only supported picture files."); 
            }

        });

        this.on("success", function (file,response) {

            // alert(">>"+response.success);
            // alert(">>"+response.filepath);
           
        });

   
        this.on("removedfile", function (file) {

            alert(file.name);

            if (rmvFile) {
                $.ajax({
                    url: path, //your php file path to remove specified image
                    type: "POST",
                    data: {
                        filenamenew: rmvFile,
                        type: 'delete',
                    },
                });
            }
        });

    }

   
});
