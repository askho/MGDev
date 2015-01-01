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