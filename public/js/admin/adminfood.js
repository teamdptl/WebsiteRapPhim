
$("#tableMovie tr").click(function(){
    var $id =$(this).closest("tr").attr("idMovie")
    $("#movie-id").val($id)
    eventGetMovieName()
    $("#formMovie").css("display","none")
})
$("#showList").click(function(){
    $("#formMovie").css("display","block")
})
$("#exit-formMovie").click(function(){
    $("#formMovie").css("display","none")
})

$("#tableCinema tr").click(function(){
    var $id =$(this).closest("tr").attr("roomID")
    console.log($id)
    $("#room-id").val($id)
    eventGetCinemaName()
    $("#formCinema").css("display","none")
})
$("#showListCinema").click(function(){
    $("#formCinema").css("display","block")
   
})
$("#exit-formCinema").click(function(){
    $("#formCinema").css("display","none")
})