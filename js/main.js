/*
Initalizing handlers 
*/
$( document ).ready(function() {
    $(".nav.navbar-nav.navbar-right").click(function(event){
        if($(event.target).is("#gallery")) {
            history.pushState(null, null, "viewCategories.php")
            event.preventDefault();
            getCategories();
        } else if($(event.target).is("#blog")) {
            alert("Blog was pressed");
        } else if($(event.target).is("#booking")) {
            alert("Booking was pressed");
        }
    })
});
/*
    This checks the URL and updates the content accordingly. 
    This allows for ajax calls while still being able to use the
    forward and backwards button
*/
$(window).on('popstate', function() {
    var fileName = location.pathname.split("/").pop();
    if(fileName == "viewAlbum.php") {
        getAlbumThumbs(getQueryVariable("categoryID"));
    } else if(fileName == "viewPhotos.php") {
        showPictures(getQueryVariable("albumID"), getQueryVariable("albumName"));
    } else if(fileName == "viewCategories.php") {
        getCategories();
    }
});
/*
    This grabs the get varibles from the URL
*/
function getQueryVariable(variable)
{
       var query = window.location.search.substring(1);
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return decodeURI(pair[1]);}
       }
       return(false);
}
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
            var url = "viewPhotos.php?albumID="+albumID+"&albumName="+albumName;
            $("#isotopeContainer").append(" <figure class = 'isotopeElement' id = '"+albumID+"'>\
                                                <a href = '"+url+"''>\
                                                <img src='"+result+"'>\
                                                <figcaption>"+albumName+"</figcaption>\
                                                </a>\
                                            </figure>");
            (function(albumID2, albumName2, href) {
                $("#"+albumID2).click(function(event) {
                    showPictures(albumID2, albumName2);
                    if(history.pushState) {
                        history.pushState(null, null, href)
                        event.preventDefault();
                    }
                });
            })(albumID, albumName, url)

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
                                                <a href = '#'>\
                                                <img src='"+thumbnail+"'>\
                                                <figcaption>"+photoName+"</figcaption>\
                                                </a>\
                                            </figure>");
            (function(j) {
                $("#"+j).click(function() {
                    alert(j + "has been clicked");
                    event.preventDefault();
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
        if(data == ""){
            alert("No categories found");
        } else {
            $("#content").html("<div id = 'categories'><h1>Categories</hi></div>");
            for(i = 0; i < data.length; i++) {
                var url = "viewAlbum.php?categoryID="+data[i]['categoryID'];
                $("#categories").hide().append("<a href = '"+url+"' id ='"+data[i]['categoryID']+"'>"+data[i]['categoryName']+"</a>").fadeIn("fast");
                (function(j) {
                    $("#"+data[j]['categoryID']).click(function(event) {
                        getAlbumThumbs(data[j]['categoryID']);
                        var href = $(this).attr('href');
                        if(history.pushState) {
                            history.pushState(null, null, href)
                            event.preventDefault();
                        }
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