var popUpContainer = document.querySelector('.popUp-container');
popUpContainer.addEventListener('click', function(e){
    if(e.target.classList.contains('popUp-container')){
        popUpContainer.style.display = "none";
    };
})

function viewDetailBooking(booking){
    var bookingID = Number(booking.getAttribute('bookingID'));
    var isPaid = booking.getAttribute('isPaid');
    console.log(isPaid);
    var boolPaid = (isPaid=='true');
    console.log(boolPaid);
    $.ajax({
        url:'/profile',
        method: 'POST',
        data: {
            bookingID:bookingID,
            isPaid: boolPaid
        },
        success:function(respone){
            var dataJson = JSON.parse(respone);
            if(dataJson != []){
                renderDetailBooking(dataJson);
            }else{
                alert("Booking id:" +bookingID + " not exist!");
            }
        }
    })
}

function renderDetailBooking(data){
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

    console.log(data['isPaid']);
    if(data['isPaid'] == "true"){
        statusBooking.style.color = "#13e502";
        statusBooking.innerHTML = "Hóa đơn đã thanh toán thành công! <i class='bx bx-check' style='color:#13e502; margin-left:5px'></i>";
    }else{
        statusBooking.style.color = "#f8cb08";
        statusBooking.innerHTML = "Đang chờ kiểm duyệt! <i class='bx bx-loader-circle' style='color:#f8cb08; margin-left:5px'></i>";
    }
    tbFood.innerHTML = htmlTbFood;
    popUpContainer.style.display = "flex";
    document.querySelector('.detail-booking').style.setProperty('animation', 'detail-booking 0.2s linear 0s alternate')
document.querySelector('.detail-booking').style.setProperty('animation-fill-mode', 'forwards')

}