let showID;
    
$("#btn-addShowtime").click(function () {
    document.getElementById("formAdd").reset();
    document.getElementById("container-button").innerHTML = `   
            <button class="btn btn-primary button" id="btn-comfirm"> Xác nhận </button>
            <button class="btn btn-danger button" id="btn-exit"> Hủy </button>`
    btnAddEvent();
    btnExitEvent();
    document.getElementById("formAdd").style.display = "flex"

})
$(".delShowTime").click(function () {

});
$(".editShowTime").click(function (e) {
    document.getElementById("formAdd").style.display = "flex";
    document.getElementById("container-button").innerHTML = `   
            <button class="btn btn-primary button" id="btn-edit"> Xác nhận </button>
            <button class="btn btn-danger button" id="btn-exit"> Hủy </button>`
    showID = e.target.getAttribute("showid");
    document.getElementById("formAdd").reset();
    btnEditEvent();
    btnExitEvent();
    $.ajax({
        url: "adminShowTime/edit",
        method: "GET",
        dataType: "json",
        data: {
            showID: showID,
        },
        success: function (res) {
            const [date, showStart] = res[0].timeStart.split(' ');
            $("#movie-id").val(res[0].movieID)
            $("#movie-name").val(res[0].movieName)
            $("#room-id").val(res[0].roomID)
            $("#cinema-name").val(res[0].cinemaName)
            $("#room-name").val(res[0].roomName)
            $("#show-start").val(showStart)
            $("#date").val(date)
            $("#during-time").val(res[0].duringTime)
        }
    })

});
$("#movie-id").focusout(function () {
    let id = $("#movie-id").val();
    console.log(id);
    url = "/adminShowTime/getMovieName";
    $.ajax({
        dataType: 'json',
        url: "/adminShowTime/getMovieName",
        method: "GET",
        data: {
            id: id,
        },
        success: function (res) {
            console.log(res);
            if (Object.keys(res).length == 0) {
                $("#error-movie-id").css("display", "inline-block");
                $("#movie-name").val("");
            }
            else {
                $("#error-movie-id").css("display", "none");
                $("#movie-name").val(res.movieName);
            }
        },

    })
})
$("#room-id").focusout(function () {
    let id = $("#room-id").val();
    $.ajax({
        dataType: 'json',
        url: "/adminShowTime/getRoomName",
        method: "GET",
        data: {
            id: id,
        },
        success: function (res) {
            if (Object.keys(res).length == 0) {

                $("#error-room-id").css("display", "inline-block");
                $("#room-name").val("");
                $("#cinema-name").val("");
            }
            else {
                $("#error-room-id").css("display", "none");
                $("#room-name").val(res[0].roomName);
                $("#cinema-name").val(res[0].cinemaName);
            }
        },

    })
})
function btnExitEvent() {
    $("#btn-exit").click(function (e) {
        e.preventDefault();
        document.getElementById("formAdd").style.display = "none"
    });
}
function btnEditEvent() {
    $("#btn-edit").click(function (e) {
        console.log("btn-edit")
        e.preventDefault();
        console.log(showID);
        if ($("#room-name").val() == "" || $("#movie-name").val() == "" || $("#date").val() == "" || $("#show-start").val() == "" || $("#during-time").val() == "") {
            console.log("error")
        }

        else {
            $.ajax({
                dataType: 'json',
                url: "adminShowTime/edit",
                method: "POST",
                data: {
                    showID: showID,
                    movieID: $("#movie-id").val(),
                    roomID: $("#room-id").val(),
                    duringtime: $("#during-time").val(),
                    timeStart: $("#date").val() + " " + $("#show-start").val() + ":00",
                },
                success: function (res) {

                    Swal.fire({
                        icon: res.type,
                        title: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                    if (res.type == "success") {
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    };
                },
            })

        }
    })
}
function btnAddEvent() {
    $("#btn-comfirm").click(function (e) {
        e.preventDefault();
        console.log(showID);
        if ($("#room-name").val() == "" || $("#movie-name").val() == "" || $("#date").val() == "" || $("#show-start").val() == "" || $("#during-time").val() == "") {
            console.log("error")
        }
        else {
            $.ajax({
                dataType: 'json',
                url: "adminShowTime/insert",
                method: "POST",
                data: {
                    movieID: $("#movie-id").val(),
                    roomID: $("#room-id").val(),
                    duringtime: $("#during-time").val(),
                    timeStart: $("#date").val() + " " + $("#show-start").val() + ":00",
                },
                success: function (res) {
                    Swal.fire({
                        icon: res.type,
                        title: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                    if (res.type == "success") {
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    };
                },
            })

        }
    })
}