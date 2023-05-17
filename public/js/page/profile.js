function viewDetailBooking(booking){
    var bookingID = Number(booking.getAttribute('bookingID'));
    var isPaid = booking.getAttribute('isPaid');
    
    $.ajax({
        url:'',
        method: 'POST',
        data: {
            bookingID:bookingID,
            isPaid:Boolean(isPaid)
        },
        success:function(respone){
            var dataJson = JSON.parse(respone);
            if(dataJson != []){
                renderDetailBooking(dataJson, isPaid);
            }else{
                alert("Booking id:" +bookingID + " not exist!");
            }
        }
    })
}

function renderDetailBooking(data, isPaid){
    var popUpContainer = document.querySelector('.popUp-container');
    var imageMovie = document.querySelector('#movie-image');
    var cinemaName = document.querySelector('#cinema-name');
    var movieName = document.querySelector('#movie-name');
    var roomName = document.querySelector('#room-name');
    var seatsName = document.querySelector('#seats-name');
    var showtime = document.querySelector('#showtime');
    var tbFood = document.querySelector('.content-food');
    var costSeat = document.querySelector('#ticket-cost');
    var costFood = document.querySelector('#food-cost');
    var costTotal = document.querySelector('#total-cost');
    var statusBooking = document.querySelector('.status-booking');

    cinemaName.innerHTML = data['cinema']['cinemaName'];
    movieName.innerHTML = data['movie']['movieName'];
    roomName.innerHTML = data['room']['roomName'];
    seatsName.innerHTML = data['seatListName'];
    showtime.innerHTML = data['time'];
    costSeat.innerHTML = data['seatPrice'];
    costFood.innerHTML = data['foodPrice'];
    costTotal.innerHTML = data['total'];

    var htmlTbFood = '';
    for(let i = 0; i < data['foodList'].length; i++){
        htmlTbFood += `<div class="fl-r-sp-btw t-bd">
                        <div class="food-name w-260px">${data['foodList'][i]['foodName']}</div>
                        <div class="food-quantity w-20px fl-r-center">${data['foodList'][i]['foodUnit']}</div>
                        </div>`
    }

    if(data['isPaid']){
        statusBooking.innerHTML = "Hóa đơn đã thanh toán thành công! <i class='bx bx-check' style='color:#13e502'></i>";
    }else{
        statusBooking.innerHTML = "Đang chờ kiểm duyệt! <i class='bx bx-loader-circle' style='color:#f8cb08'  ></i>";
    }
    tbFood.innerHTML = htmlTbFood;
    popUpContainer.style.display = "flex";

}