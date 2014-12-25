$( document ).ready(function() {
	$(".nav.navbar-nav.navbar-right").click(function(event){
		if($(event.target).is("#gallery")) {
			alert("gallery was pressed");
		} else if($(event.target).is("#blog")) {
			alert("Blog was pressed");
		} else if($(event.target).is("#booking")) {
			alert("Booking was pressed");
		}
	})
	$( "#albumSelector" ).change(function() {
		$("#albumName").val("");
  	});
	getAlbums();
});
var fileNames = [];
var JSONFileName;
Dropzone.options.testZone = {
    init: function() {
        this.on("addedfile", function(file) {
            $("#previewWell").show(100);
        });
        this.on("processing", function(file) {
          $("#submit").prop('disabled', true);
          $("#submit").addClass('btn-danger').removeClass('btn-success')
          $("#submit").html("Waiting For Upload");
        });
        this.on("totaluploadprogress", function(progress) {
          $("#progress").val(progress);
        });
    },
    maxFiles: null,
    maxFilesize: 50,
    acceptedFiles: ".gif,.GIF,.png,.PNG,.jpg,.JPG,.jpeg,.JPEG",
    previewsContainer: "#previewArea",
    parallelUploads: 20,
    accept: function(file, done) {
        done();
    },
    complete: function(file){
        JSONFileName = JSON.stringify(fileNames);
        document.getElementById('jsonField').value = JSONFileName;
        $("#submit").prop('disabled', false);
        $("#submit").removeClass('btn-danger').addClass('btn-success')
        $("#submit").html("Continue");
    }
    ,
    success: function(responseText) {
        fileNames.push(responseText.xhr.responseText);
    },
    

};
function getAlbums() {
	$("#albumSelector").html("<option value='null'>Loading</option>");
	$.ajax({
  	url: "../php/getAlbums.php",
  	dataType: "json"
	})
  	.done(function( data ) {
  		$("#albumSelector").html("<option value='null'>Select An Option</option>");
  		for(i = 0; i < data.length; i++) {
  			$("#albumSelector").append("<option value='"+data[i][0]+"'>"+data[i][0]+"</option>");
  		}
  });
}