/*
Initalizing handlers 
*/
$( document ).ready(function() {
    $(".nav.navbar-nav.navbar-right").click(function(event){
        if($(event.target).is("#gallery")) {
            getAlbumThumbs();
        } else if($(event.target).is("#blog")) {
            alert("Blog was pressed");
        } else if($(event.target).is("#booking")) {
            alert("Booking was pressed");
        }
    })
});
function getAlbumThumbs() {
    $.ajax({
        url: "php/albumThumbnails.php",
        dataType: "json"
    })
    .done(function( data ) {
        for(i = 0; i < data.length; i++) {
            var result = "images/thumbnails/"+data[i].location;
            $("#content").append("<img src = '"+result+"'/>");
        }
    });
}