var ticketPriceTag = document.getElementById('price-ticket');
var foodPriceTag = document.getElementById('price-food');
var totalPriceTag = document.getElementById('price-total');
var price = 0;
var foodPrice = 0;
var total = 0;
var listSeat = document.getElementById('main-box').getElementsByClassName('seat');
var listRealCheckedPos = [];
var listRealCheckedID = []
var showID = document.getElementById('top-header-content').getAttribute('showID');

var boxlimit = 0; //lưu số lượng ghế đã chọn (tối đa 8)

function handleViewBookingPage(button){
    
    var currentPos = button.getAttribute('current-pos');
    var foodBox = document.getElementById('box-food');
    var seatBox = document.getElementById('box-seat');
    var leftBtn = document.getElementById('button-pre');
    var rightBtn = document.getElementById('button-next');

    switch (currentPos) {
        case "choose-seat":
            if(listRealCheckedID.length > 0){
                if(boxlimit <= 8){
                    if(isValidSeatsChecked()){
                        //Đẩy data xuống Booking controller
                        $.ajax({
                            url: '/booking',
                            data: {
                                listSeatID: listRealCheckedID,
                                showTimeID: showID
                            },
                            type: post,
                            success: function(respone) {
                                console.log(respone);
                            }
                        })
                        foodBox.style.display = "flex";
                        seatBox.style.display = "none";
                        leftBtn.style.opacity = 1;
                        leftBtn.style.pointerEvents = 'fill';
                        leftBtn.setAttribute('current-pos', 'choose-food');
                        rightBtn.setAttribute('current-pos', 'choose-food');       
                    }else alert("Please don't leave 1 seat empty between your seats!^.^");
                }else alert("You can choose maximum 8 seats")
            }else alert("Please choose your seat first")         
            break;
    
        case "choose-food":
            if(button.id == "button-pre"){
                foodBox.style.display = "none";
                seatBox.style.display = "block";
                leftBtn.style.opacity = 0.6;
                leftBtn.style.pointerEvents = 'none';
                leftBtn.setAttribute('current-pos', 'choose-seat');
                rightBtn.setAttribute('current-pos', 'choose-seat');
            }else{
                leftBtn.setAttribute('current-pos', 'payment');
                rightBtn.setAttribute('current-pos', 'payment');
                rightBtn.innerHTML = `Payment
                <i class='bx bx-dollar-circle bx-spin' ></i>`;
            }   
            break;
        default:
            if(button.id == "button-pre"){
                foodBox.style.display = "flex";
                seatBox.style.display = "none";
                leftBtn.setAttribute('current-pos', 'choose-food');
                rightBtn.setAttribute('current-pos', 'choose-food');
                rightBtn.innerHTML = `Next
                <i class='bx bx-skip-next-circle bx-tada bx-rotate-270'></i>`;
            }else{
                //Viết ajax đẩy dữ liệu xuống php
            }   
            break;
    }

}

function selectedSeats(seat){
    var listCheckedSeat = document.getElementById('main-box').getElementsByClassName('seat-checked');
    var seatPos = seat.innerText;
    price = 0;
    boxlimit = listCheckedSeat.length;

    if(boxlimit > 0){

        for(let i = 0; i < boxlimit; i++){
            console.log(listCheckedSeat[i]);
            price += parseInt(listCheckedSeat[i].getAttribute('price'))
        }


        var seatType = listCheckedSeat[0].getAttribute('zone');
        
            if(seat.classList.contains('seat-checked')){
                seat.classList.remove('seat-checked');
                price -= parseInt(seat.getAttribute('price'));
                listRealCheckedPos.splice(listRealCheckedPos.indexOf(seatPos), 1);
                listRealCheckedID.splice(listRealCheckedID.indexOf(seat.id), 1);
                boxlimit--;
            }else{
                if(boxlimit < 8){
                    if(seatType === seat.getAttribute('zone')){
                        seat.classList.add('seat-checked');
                        price += parseInt(seat.getAttribute('price'));
                        listRealCheckedPos.push(" " + seatPos);
                        listRealCheckedID.push(seat.id);
                        boxlimit++;
                    }else{
                        alert("Please choose same type of seats^.^");
                    }
                 }else{alert('You can choose maximum 8 seats')}

            }
        
    }else{    
        seat.classList.add('seat-checked');
        price += parseInt(seat.getAttribute('price'));
        listRealCheckedPos.push(" " + seatPos);      
        listRealCheckedID.push(seat.id);  
        boxlimit++;    
    }
    

    
    
    ticketPriceTag.innerHTML = `${formatNumberToMoney(price)}`;
    ticketPriceTag.setAttribute('value', price);

    total = foodPrice + price;
    totalPriceTag.innerHTML = `${formatNumberToMoney(total)}`;
    ticketPriceTag.setAttribute('value', total);

    if(listRealCheckedPos.length > 0){
        document.getElementById('seat-code').innerHTML=`<p class="title">STD </p>
                                                    <p class="nd" id="pos">${listRealCheckedPos.toString()}</p>`
    }else{
        document.getElementById('seat-code').innerHTML="";
    }
    
}

function isValidSeatsChecked(){
    var seat = null
    var preSeat = null
    var isValidSeat = true;
    var nextSeat = null

    for(let i = 0; i < listSeat.length; i++){
        seat = listSeat[i];
        if(seat.classList.contains('seat-checked')){
            preSeat = listSeat[i-1];
            nextSeat = listSeat[i+1];
            console.log("Pre: ")
            console.log(preSeat)
            console.log("Next: ")
            console.log(nextSeat)
            if(!preSeat.classList.contains('start-col') && !nextSeat.classList.contains('final-col')){
                //ghế hợp lệ khi
                //Xét bên trái: ghế kề được chọn/đã mua/trống hoặc ghế cách 1 ghế không được chọn/đã mua/trống
                //Xét bên phải: tương tự
                //Cả hai đều đúng thì ghế sẽ hợp lệ
                isValidSeat = ((    !(listSeat[i-2].classList.contains('empty') || 
                                    listSeat[i-2].classList.contains('seat-checked') ||
                                    listSeat[i-2].classList.contains('seat-booked')) 
                                    ||
                                    (preSeat.classList.contains('seat-checked') ||
                                    preSeat.classList.contains('seat-booked'))) 
                                    &&
                                    (!(listSeat[i+2].classList.contains('empty') || 
                                    listSeat[i+2].classList.contains('seat-checked') ||
                                    listSeat[i+2].classList.contains('seat-booked'))
                                    ||
                                    (nextSeat.classList.contains('seat-checked') ||
                                    nextSeat.classList.contains('seat-booked')))
                            );
                console.log(isValidSeat);
                if(!isValidSeat) return false;
            }
        }
    }
    return true;
}


//Hàm chuyển dạng số thành dạng tiền
function formatNumberToMoney(number){
    var money = number.toString().split('');
    var count = 0;
    for(let i = (money.length - 1); i > 0 ; i--){
        count++;
        if(count == 3){
            money.splice(i, 0, ',');
            count = 0;
        }
    }
    return money.join('');
}