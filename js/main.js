/*
Initalizing handlers 
*/
$( document ).ready(function() {
    $(".nav.navbar-nav.navbar-right").click(function(event){
        if($(event.target).is("#gallery")) {
            getCategories();
        } else if($(event.target).is("#blog")) {
            alert("Blog was pressed");
        } else if($(event.target).is("#booking")) {
            alert("Booking was pressed");
        }
    })
    $(".navbar-brand").click(function() {
        $("#content").fadeOut(function(){
            $("#content").html("");
            $("#content").show();
        });
        
    })
});
/*
    This grabs the thumbnails for a category and places them inside content and inits isotope
    @param: categoryID This is the category that we are selecting
*/
function getAlbumThumbs(categoryID) {
    $.ajax({
        url: "php/albumThumbnails.php",
        dataType: "json",
        type: "POST",
        data:{category: categoryID}
    })
    .done(function( data ) {
        console.log(data);
        $("#content").html("<h1>Albums</hi>");
        $("#content").append("<div id ='isotopeContainer'></div>");
        $("#content").hide();
        if(data == ""){
            alert("No albums found");
        }
        for(i = 0; i < data.length; i++) {
            var result = "images/thumbnails/"+data[i].FirstPhoto;
            var albumName = data[i].albumname;
            var albumID = data[i].albumID;
            $("#isotopeContainer").append(" <figure class = 'isotopeElement' id = '"+albumID+"'>\
                                                <a href = '#''>\
                                                <img src='"+result+"'>\
                                                <figcaption>"+albumName+"</figcaption>\
                                                </a>\
                                            </figure>");
            (function(albumID2, albumName2) {
                $("#"+albumID2).click(function() {
                    showPictures(albumID2, albumName2);
                });
            })(albumID, albumName)

        }
        $("#content").fadeIn();
        initIsotope();
    })
    .fail(function() {
        alert("Failed to get the albums");
    });
}

function showPictures(albumID, albumName) {
    $.ajax({
        url: "php/getPhotos.php",
        dataType: "json",
        type: "POST",
        data:{album: albumID}
    })
    .done(function( data ) {
        console.log(data);
        $("#content").html("<h1>"+albumName+"</hi>");
        $("#content").append("<div id ='isotopeContainer'></div>");
        $("#content").hide();
        if(data == ""){
            alert("No albums found");
        }
        for(i = 0; i < data.length; i++) {
            var thumbnail = "images/thumbnails/"+data[i].location;
            var photoName = data[i].photoName;
            var photoID = data[i].photoID;
            if(photoName == null) {
                photoName = "";
            }
            $("#isotopeContainer").append(" <figure class = 'isotopeElement' id = '"+photoID+"'>\
                                                <a href = '#''>\
                                                <img src='"+thumbnail+"'>\
                                                <figcaption>"+photoName+"</figcaption>\
                                                </a>\
                                            </figure>");
            (function(j) {
                $("#"+j).click(function() {
                    alert(j + "has been clicked");
                });
            })(photoID)

        }
        $("#content").fadeIn();
        initIsotope();
    })
    .fail(function() {
        alert("Failed to get the albums");
    });
}
/*
    This grabs the categories to be shown.
*/
function getCategories() {
    $.ajax({
        url: "php/getCategories.php",
        dataType: "json"
    })
    .done(function( data ) {
        console.log(data);
        if(data == ""){
            alert("No categories found");
        } else {
            $("#content").html("<div id = 'categories'><h1>Categories</hi></div>");
            for(i = 0; i < data.length; i++) {
                $("#categories").hide().append("<a href = '#' id ='"+data[i]['categoryID']+"'>"+data[i]['categoryName']+"</a>").fadeIn("fast");
                (function(j) {
                    $("#"+data[j]['categoryID']).click(function() {
                    getAlbumThumbs(data[j]['categoryID']);
                    });
                })(i)
                
            }
        }
    })
    .fail(function() {
        alert("Failed to get the categories");
        $("#content").html("");
    })
}
/*
    This initalizes isotope
*/
function initIsotope() {
    var $container = $('#isotopeContainer').imagesLoaded( function() {
      $container.isotope({
          itemSelector: '.isotopeElement',
          masonry: {
            gutter: 15
        }
    });
  });
}