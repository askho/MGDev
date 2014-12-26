$( document ).ready(function() {
	$(".nav.navbar-nav.navbar-right").click(function(event){
		if($(event.target).is("#addPhotos")) {
			alert("add photos was pressed");
		} else if($(event.target).is("#editPhotos")) {
			alert("edit photos was pressed");
		} else if($(event.target).is("#editBlog")) {
			alert("blog was pressed");
		}
	})
	$( "#albumSelector" ).change(function() {
		$("#albumName").val("");
  	});
  $( "#categorySelector" ).change(function() {
    $("#category").val("");
  });
	initUploadScreen();
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
/**
  This function sets up the category, and album selector based on what is returned from categoryAlbumData.php
*/
function initUploadScreen() {
	$("#albumSelector").html("<option value='null'>Loading</option>");
  $("#categorySelector").html("<option value='null'>Loading</option>");
	$.ajax({
  	url: "../php/categoryAlbumData.php",
  	dataType: "json"
	})
  	.done(function( data ) {
  		$("#albumSelector").html("<option value='null'>Select An Option</option>");
  		for(i = 0; i < data[0].length; i++) {
  			$("#albumSelector").append("<option value='"+data[0][i]['albumName']+"'>"+data[0][i]['albumName']+"</option>");
  		}
      $("#categorySelector").html("<option value='null'>Select An Option</option>");
      for(i = 0; i < data[1].length; i++) {
        $("#categorySelector").append("<option value='"+data[1][i]['categoryName']+"'>"+data[1][i]['categoryName']+"</option>");
      }
      
  });
}