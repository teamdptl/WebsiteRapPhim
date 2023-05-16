let showID;



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
            for (let i = 0; i < res.length; i++) {
                appendSeat();
            };
            checkSeatList();
            var select = document.getElementsByClassName("selectType");
            var input = document.getElementsByClassName("seat-price");
            for (let i = 0; i < res.length; i++) {
                select[i].value = res[i].seatType;
                input[i].value = res[i].ticketPrice;
            }; 
            const [date, showStart] = res[0].timeStart.split(' ');
            $("#movie-id").val(res[0].movieID);
            $("#movie-name").val(res[0].movieName);
            $("#room-id").val(res[0].roomID);
            $("#cinema-name").val(res[0].cinemaName);
            $("#room-name").val(res[0].roomName);
            $("#show-start").val(showStart);
            $("#date").val(date);
            $("#during-time").val(res[0].duringTime);

        }
    })

});
$("#movie-id").focusout(function(){
    eventGetMovieName()
});
function eventGetMovieName(){
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
}
$("#room-id").focusout(function () {
    eventGetCinemaName();
})
function eventGetCinemaName(){
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
}
function btnExitEvent() {
    $("#btn-exit").click(function (e) {
        document.getElementById("formAdd").style.display = "none"
        $("#seatList").empty();
    });
}
function btnEditEvent() {
    $("#btn-edit").click(function (e) {
        e.preventDefault();
        if (checkInput()) {
            Swal.fire({
                icon: 'error',
                title: 'Vui lòng nhập đủ các trường dữ liệu',
                text: 'Something went wrong!',
            })
            return;
        }
        else {
            var selected = [];
            var seatPrice = [];
            var i = 0;
            $('select').each(function () { // for each set loại ghế
                selected[$(this).val()] = $(this).find("option:selected").val();
            })
            selected = selected.filter((str) => str !== ''); // loại bỏ empty emlement
            $('.seat-price').each(function () { // for each set giá ghế
                seatPrice[i] = $(this).val();
                i++;
            })
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
                    seatType: selected,
                    seatPrice: seatPrice,
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
function checkSeat() {

}
function checkInput() {
    let Ischeck;



    $('select').each(function () {
        // selected[$(this).val()] = $(this).find("option:selected").text();
        if (this.value == "0") {
            Ischeck = true;

        }
    }
    )
    // console.log($(".seat-price"));
    $(".seat-price").each(function () {
        // console.log($(this).val())
        if (this.value == "") {
            Ischeck = true;
        }
    }
    )
    if (Ischeck) {
        return true;
    }
    if ($("#room-name").val() == "" || $("#movie-name").val() == "" || $("#date").val() == "" || $("#show-start").val() == "" || $("#during-time").val() == "") {
        return true
    }
}
function btnAddEvent() {
    $("#btn-comfirm").click(function (e) {
        e.preventDefault();
        if (checkInput()) {
            Swal.fire({
                icon: 'error',
                title: 'Vui lòng nhập đủ các trường dữ liệu',
                text: 'Something went wrong!',
            })
            return;
        }
        else {
            var selected = [];
            var seatPrice = [];
            var i = 0;
            $('select').each(function () { // for each set loại ghế
                selected[$(this).val()] = $(this).find("option:selected").val();
            })
            selected = selected.filter((str) => str !== ''); // loại bỏ empty emlement
            $('.seat-price').each(function () { // for each set giá ghế
                seatPrice[i] = $(this).val();
                i++;
            })
            // console.log(seatPrice);
            $.ajax({
                dataType: 'json',
                url: "adminShowTime/insert",
                method: "POST",
                data: {
                    movieID: $("#movie-id").val(),
                    roomID: $("#room-id").val(),
                    duringtime: $("#during-time").val(),
                    timeStart: $("#date").val() + " " + $("#show-start").val() + ":00",
                    seatType: selected,
                    seatPrice: seatPrice,
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