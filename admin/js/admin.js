$( document ).ready(function() {
    $("#createPost").hide();
    $("#postEditor").hide();
    $("#editPosts").hide();
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
    $("#reset").click(function() {
        initUploadScreen();
    })
    initUploadScreen();
    
    CKEDITOR.replace( "postBody",{
        height: "40vh",
        removePlugins: 'sourcearea'
    });
    $("#createNewPost").click(function(){
        $("#contentWell").children().fadeOut("fast");
        $("#blogMenu").fadeIn();
        $("#createPost").fadeIn();
        window.scrollTo(0,0);
    });
    $("#editOldPost").click(function() {
        initOldBlogPosts();
        $("#contentWell").children().fadeOut("fast");
        window.scrollTo(0,0);
        $("#blogMenu").fadeIn();
        $("#editPosts").fadeIn();
    });
    $("#createPostsButton").click(function() {
        
        $.ajax({
            url: "php/createPost.php"
            ,type: "POST"
            ,data: {postTitle : $("#postTitle").val()
            ,postBody : CKEDITOR.instances['postBody'].getData()}
        }).done(function(data){
            if(data == "1") {
                $("#notificationMessage").html("Post Created");
                $('#notification2').modal('show');
                $("#postTitle").val("");
                CKEDITOR.instances['postBody'].setData("");
            } else {
                $("#notificationMessage").html("Failed To create post");
                $('#notification2').modal('show');
            }
            
        });
    });
    $("#editPostButton").click(function() {
        $.ajax({
            url: "php/editPost.php"
            ,type: "POST"
            ,data: {editTitle : $("#editPostTitle").val()
                ,editBody : CKEDITOR.instances['editPostBody'].getData()
                ,editPostID2: editOldBlog.postID}
        }).done(function(data){
            console.log(data);
            if(data == "1") {
                $("#notificationMessage").html("Post Modified");
                $('#notification2').modal('show');
                $("#postTitle").val("");
                CKEDITOR.instances['editPostBody'].setData("");
                $("#contentWell").children().fadeOut();
                $("#blogMenu").fadeIn();
            } else {
                $("#notificationMessage").html("Failed To modify post");
                $('#notification2').modal('show');
            }
            
        });
    });
});
var fileNames = [];
var JSONFileName;
function initDropZoneGallery() {
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
}
function initDropZoneBlog() {
    Dropzone.options.blog = {
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
        parallelUploads: 20,
        dictDefaultMessage: "custom message",
        accept: function(file, done) {
            done();
        },
        success: function(responseText) {
            $("#imageLink").html(responseText.xhr.responseText);
            $('#notification').modal('show');
        },
    };  
}
function editOldBlog(postID) {
    $("#editPosts").fadeOut(function() {
        window.scrollTo(0,0);
    });
    editOldBlog.postID = postID;
    var body;
    $.ajax({
      url: "php/getPost.php",
      dataType: "json",
      type: "POST",
      data : {
        postID2 : postID
      }
    }).success(function( data ) {
        $("#editPostTitle").val(data.blogData[0].title);
        CKEDITOR.replace( "editPostBody",{
            height: "40vh",
            removePlugins: 'sourcearea'
        });
        CKEDITOR.instances['editPostBody'].setData(data.blogData[0].body);
        $("#postEditor").fadeIn();

    }) .fail(function() {
        alert("failed to grab data");
    });
        
}
function initOldBlogPosts() {
    $.ajax({
      url: "php/getBlogData.php",
      dataType: "json"
    })
      .success(function( data ) {
        $("#postTable").html("<tr><th>Post Title</th><th>Created On</th><th>Edit</th></tr>")
        for(i = 0; i < data.blog.length; i++) {
            
            $("#postTable").append("<tr><td>"+data.blog[i].title+"</td><td>"+data.blog[i].date
                +"</td><td><button type='button' id = 'editBlog"+data.blog[i].postID+"'class='btn btn-success'>Edit</button> "
                +"<button type ='button' id = 'deleteBlog"+data.blog[i].postID+"' class = 'btn btn-danger'>Delete</button></td></tr>");
            //Adding listeners to edit button
            (function(j) {
                $("#editBlog"+data.blog[j].postID).click(function(){
                    editOldBlog(data.blog[j].postID);
                });
                $("#deleteBlog"+data.blog[j].postID).click(function() {
                    deletePost(data.blog[j].postID);
                    
                });
            })(i);
        }
      });
}
function deletePost(postID) {
    var body;
    $.ajax({
      url: "php/deletePost.php",
      type: "POST",
      data : {
        postID2 : postID
      }
    }).success(function( data ) {
        console.log(data);
        if(data == "1") {
            $("#notificationMessage").html("Deletion Successful");
            $('#notification2').modal('show');
        } else {
            $("#notificationMessage").html("Failed to delete post");
            $('#notification2').modal('show');
        }

    }) .fail(function() {
        alert("failed to find post");
    }). complete(function() {
        initOldBlogPosts();
    }); 
}
/**
This function sets up the category, and album selector based on what is returned from categoryAlbumData.php
*/
function initUploadScreen() {
    $("#category").prop('disabled', false);
    $("#albumName").prop('disabled', false);
    $("#albumName").val("");
    $("#category").val("");
    $("#albumSelector").prop('disabled', false);
    $("#categorySelector").prop('disabled', false);
    $("#category").keydown(function() {
        $("#albumSelector").html("<option value='null'>Select An Option</option>");
        $("#albumSelector").prop('disabled', true);
        $("#categorySelector").html("<option value='null'>Select An Option</option>");
        $("#categorySelector").prop('disabled', true);
    });
    $("#albumName").keydown(function() {
        $("#albumSelector").html("<option value='null'>Select An Option</option>");
        $("#albumSelector").prop('disabled', true);
        $("#categorySelector").html("<option value='null'>Select An Option</option>");
        $("#categorySelector").prop('disabled', true);
    });
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
        $("#albumSelector").change(function(){
            $("#category").unbind("keydown");
            $("#albumName").unbind("keydown");
            $("#categorySelector").html("");
            $("#category").keydown(function() {
                $("#categorySelector").html("<option value='null'>Select An Option</option>");
                $("#categorySelector").prop('disabled', true);
            });
            var currentlySelected = $(this).val();
            $("#albumName").prop('disabled', true);
            for(i = 0; i < data[2].length; i++) {
                if(data[2][i]['albumName'] == currentlySelected) {
                    $("#categorySelector").append("<option value='"+data[2][i]['categoryName']+"'>"+data[2][i]['categoryName']+"</option>");
                }
            }
        });
        $("#categorySelector").change(function(){
            $("#category").unbind("keydown");
            $("#albumName").unbind("keydown");
            $("#albumSelector").html("");
            $("#albumName").keydown(function() {
                $("#albumSelector").html("<option value='null'>Select An Option</option>");
                $("#albumSelector").prop('disabled', true);
            });
            var currentlySelected = $(this).val();
            $("#category").prop('disabled', true);
            for(i = 0; i < data[2].length; i++) {
                if(data[2][i]['categoryName'] == currentlySelected) {
                    $("#albumSelector").append("<option value='"+data[2][i]['albumName']+"'>"+data[2][i]['albumName']+"</option>");
                }
            }
        });
    });
}