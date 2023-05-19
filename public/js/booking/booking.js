var ticketPriceTag = document.getElementById('price-ticket');
var foodPriceTag = document.getElementById('price-food');
var totalPriceTag = document.getElementById('price-total');
var price = 0;
var foodPrice = 0;
var total = 0;
var methodPay = '';
var listSeat = document.getElementById('main-box').getElementsByClassName('seat');
var listRealCheckedPos = [];
var listRealCheckedID = []
var showID = document.getElementById('top-header-content').getAttribute('showID');
var listFood = [];

var boxlimit = 0; //lưu số lượng ghế đã chọn (tối đa 8)

function handleBooking(button){

    var topHeader = document.getElementById('top-header-content');
    var boxTitle = document.querySelector('.box-title');
    var mainTitle = boxTitle.querySelector('.title');
    
    var currentPos = button.getAttribute('current-pos');
    var paymentBox = document.getElementById('payment-box');
    var foodBox = document.getElementById('box-food');
    var seatBox = document.getElementById('box-seat');
    var leftBtn = document.getElementById('button-pre');
    var rightBtn = document.getElementById('button-next');

    var radioBunttons = document.querySelectorAll('input[name="mtd"]');
    var checkBox = document.getElementById('agreement');

    switch (currentPos) {
        case "choose-seat":
            //Nhấn nút next ở giao diện chọn ghế
            if(listRealCheckedID.length > 0){
                if(boxlimit <= 8){
                    if(isValidSeatsChecked()){
                        //Đẩy data xuống Booking controller
                        $.ajax({
                            url: '/booking',
                            method: "POST",
                            data: {
                                listRealCheckedID: listRealCheckedID,
                                showID: showID
                            },
                            success: function(respone) {
                                var jsonData = JSON.parse(respone);
                                console.log(jsonData['announcement']);
                                if(jsonData['announcement'] === 'success'){
                                    foodBox.style.display = "flex";
                                    seatBox.style.display = "none";
                                    mainTitle.innerHTML = "Choose your combo"
                                    leftBtn.style.opacity = 1;
                                    leftBtn.style.pointerEvents = 'fill';
                                    leftBtn.setAttribute('current-pos', 'choose-food');
                                    rightBtn.setAttribute('current-pos', 'choose-food');
                                    renderSeatPosition();
                                    rehandleDataForFoodBooking();
                                }else{
                                    if(jsonData['announcement'] === 'booked'){
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Lỗi người dùng',
                                            text: 'Ghế bạn muốn đã có người mua!',
                                        })
                                        handleBookedSeatAfterCheck(jsonData.listBookedID);
                                    }else{
                                        if(jsonData['announcement'] === 'login'){
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Lỗi đăng nhập',
                                                text: 'Vui lòng đăng nhập trước!',
                                            })
                                            location.href = '/signin';
                                        }else alert(jsonData['announcement']); 
                                    }
                                }
                            }
                        })
                               
                    }else alert("Please don't leave 1 seat empty between your seats!^.^");
                }else alert("You can choose maximum 8 seats")
            }else alert("Please choose your seat first")         
            break;
    
        case "choose-food":
            
            if(button.id == "button-pre"){
                //Chọn trở về ở giao diện đặt bắp nước
                foodBox.style.display = "none";
                seatBox.style.display = "block";
                mainTitle.innerHTML = "Choose your seat"
                leftBtn.style.opacity = 0.6;
                leftBtn.style.pointerEvents = 'none';
                leftBtn.setAttribute('current-pos', 'choose-seat');
                rightBtn.setAttribute('current-pos', 'choose-seat');
                
                resetListFoodQuantityToZero();
                resetBoxQuantityToZero();
                calcPopcornPrice();
                total = price + foodPrice;
                renderPriceTag(price, foodPrice, total);
            }else{
                //Tiếp tục đến giao diện thanh toán
                foodBox.style.display = "none";
                paymentBox.style.display = "flex";
                topHeader.innerHTML = "Payment";
                boxTitle.style.display = "none";
                leftBtn.setAttribute('current-pos', 'payment');
                rightBtn.setAttribute('current-pos', 'payment');
                rightBtn.innerHTML = `Payment
                <i class='bx bxs-dollar-circle bx-tada' style='color:#FAFA10' ></i>`;
                pushData();
                
            }   
            break;
        default:
            if(button.id == "button-pre"){
                // foodBox.style.display = "flex";
                // seatBox.style.display = "none";
                // leftBtn.setAttribute('current-pos', 'choose-food');
                // rightBtn.setAttribute('current-pos', 'choose-food');
                // rightBtn.innerHTML = `Next
                // <i class='bx bx-skip-next-circle bx-tada bx-rotate-270'></i>`;
                window.location.assign(`/booking?showID=${showID}`);
            }else{
                var flagCheckMethod = false;
                var flagCheckAgreement = false;
                for(let i = 0; i < radioBunttons.length; i++){
                    if(radioBunttons[i].checked){
                        methodPay = radioBunttons[i].id;
                        flagCheckMethod = true;
                    }
                }

                flagCheckAgreement = checkBox.checked;

                if(flagCheckAgreement && flagCheckMethod){
                    pushDataToBooking(methodPay);
                }else{
                    if(!flagCheckAgreement && !flagCheckMethod){
                        Swal.fire({
                            icon: 'annouce',
                            title: 'Thông báo',
                            text: 'Vui lòng chọn phương thức thanh toán và đồng ý với các điều khoản của chúng tôi',
                        })
                    }else{
                        if(!flagCheckAgreement){
                            Swal.fire({
                                icon: 'annouce',
                                title: 'Thông báo',
                                text: 'Vui lòng đồng ý với các điều khoản của chúng tôi',
                            })
                        }else Swal.fire({
                            icon: 'annouce',
                            title: 'Thông báo',
                            text: 'Vui lòng chọn phương thức thanh toán!',
                        })
                    }
                }
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
            listRealCheckedPos.splice(listRealCheckedPos.indexOf(' ' + seatPos), 1);
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
    

    
    total = foodPrice + price;
    renderPriceTag(price, foodPrice, total);
    renderSeatPosition();
}

function renderPriceTag(ticket, food, total){
    ticketPriceTag.innerHTML = `${formatNumberToMoney(ticket)}`;
    ticketPriceTag.setAttribute('value', ticket);

    foodPriceTag.innerHTML = `${formatNumberToMoney(food)}`;
    foodPriceTag.setAttribute('value', food);
    
    totalPriceTag.innerHTML = `${formatNumberToMoney(total)}`;
    totalPriceTag.setAttribute('value', total);
}

function renderSeatPosition(){
    if(listRealCheckedPos.length > 0){
        document.getElementById('seat-code').innerHTML=`<p class="title">STD </p>
                                                    <p class="nd" id="pos">${listRealCheckedPos.toString()}</p>`
    }else{
        document.getElementById('seat-code').innerHTML="";
    }
}

function isValidSeatsChecked(){
    var seat = null;
    var preSeat = null;
    var isValidSeat = true;
    var nextSeat = null;
    var countInvalidSeat = 0;
    var specialFlag = false;
    var leftFisrt = null;
    var rightLast = null;
    var tmpSeatID = listRealCheckedID.map((x) =>parseInt(x));

    for(let i = 0; i < listSeat.length; i++){
        seat = listSeat[i];
        if(seat.classList.contains('seat-checked')){
            preSeat = listSeat[i-1];
            nextSeat = listSeat[i+1];
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
                if(!isValidSeat) countInvalidSeat++;
            }

            if(seat.id == Math.min(...tmpSeatID).toString()) leftFisrt = preSeat;
            if(seat.id == Math.max(...tmpSeatID).toString()) rightLast = nextSeat;

        }
    }

    
    //Nếu không có ghê bất hợp lệ thì cho phép
    if(countInvalidSeat == 0) return true;
    // Trường hợp đặc biệt: nếu các ghế được chọn liên tiếp
    //và 1 ghế k hợp lệ thì vẫn cho phép
    console.log(leftFisrt);
    console.log(rightLast);
    specialFlag = (!leftFisrt.classList.contains('seat-checked') && rightLast.classList.contains('empty'))
                || (leftFisrt.classList.contains('seat-booked') && !rightLast.classList.contains('seat-checked'))
                || (leftFisrt.classList.contains('empty') && !rightLast.classList.contains('seat-checked'))
                || (!leftFisrt.classList.contains('seat-checked') && rightLast.classList.contains('seat-booked'));
    if(isSameRow() && countInvalidSeat == 1 && specialFlag) return true;
    return false;

}

function isSameRow(){
    var pos = listRealCheckedPos[0].split('');
    var row = pos[1];
    for(let i = 1; i < listRealCheckedPos.length; i++){
        pos = listRealCheckedPos[i].split('');
        console.log(pos);     
        console.log(row);     
        if(row == pos[1]){
            row = pos[1];
        }else{return false;}
    }
    return true;
}

function handleBookedSeatAfterCheck(listBookedID){
    var seatDiv;
    var seatPos;
    var seat;
    for(let i = 0; i < listBookedID.length; i++){
        seatDiv = document.getElementById(listBookedID[i]);
        seatDiv.classList.remove('seat-active');
        seatDiv.classList.remove('seat-checked');
        seatDiv.classList.add('seat-booked');

        seat = document.getElementById(listBookedID[i]);
        listRealCheckedID.splice(listRealCheckedID.indexOf(seat.id), 1);
        seatPos = seat.innerText;
        listRealCheckedPos.splice(listRealCheckedPos.indexOf(" " + seatPos), 1);
    }
    renderSeatPosition();
}

function rehandleDataForFoodBooking(){
    var foodDesc = document.getElementsByClassName('food-desc');
    var foodPrice = document.getElementsByClassName('price-food-text');
    var text = [];
    var desc = '';
    var price = 0;

    //handle data of food discription
    for( let i = 0; i < foodDesc.length; i++){
        text = foodDesc[i].innerHTML.split(',');
        desc = '';
        for(let j = 0; j < text.length; j++){
            desc += `<p>${text[j]}</p>`;
        }
        foodDesc[i].innerHTML = desc;
    }

    //handle data of food price
    for(let i = 0; i < foodPrice.length; i++){
        price = foodPrice[i].getAttribute('value');
        foodPrice[i].innerHTML = formatNumberToMoney(Number(price));
    }
}

function spinQuantityFood(button){
    var foodQuantity = button.parentElement;
    var foodContent = foodQuantity.parentElement;
    var fprice = Number(foodContent.querySelector('.price-food-text').getAttribute('value'));
    var quantityBox = foodQuantity.querySelector('.box-quantity');
    var calc = button.getAttribute('calc');
    var quantity = Number(quantityBox.getAttribute('value'));
    var foodID = button.parentElement.id;
    var flagFoodExist = false;
    
    if(calc === '+'){
        if(quantity < 4){
            quantity += 1;
            for(let i = 0; i < listFood.length; i++){
                
                if(listFood[i].foodID === foodID){
                    console.log(quantity);
                    listFood[i].quantity = quantity;
                    flagFoodExist = true;
                    break;
                }
            }
            if(!flagFoodExist){
                listFood.push({foodID:foodID, quantity:quantity, price:fprice})
            }
        }
    }else{
        if(calc == '-'){
            if(quantity > 0){
                quantity -= 1;
                for(let i = 0; i < listFood.length; i++){                
                    if(listFood[i].foodID === foodID){
                        listFood[i].quantity = quantity;
                        break;
                    }
                }
            }
        }
    }

    quantityBox.setAttribute('value', quantity);
    quantityBox.innerHTML=quantity;
    calcPopcornPrice();
    total = price + foodPrice;
    renderPriceTag(price, foodPrice, total);
}

function calcPopcornPrice(){
    foodPrice = 0;
    for(let i = 0; i < listFood.length; i++){
        foodPrice += listFood[i].price * listFood[i].quantity;
    }
}

function resetListFoodQuantityToZero(){
    for(let i = 0; i < listFood.length; i++){
        listFood[i].quantity = 0;
    }
}

function resetBoxQuantityToZero(){
    var listBoxQuantity = document.getElementsByClassName('box-quantity');
    for(let i = 0; i < listBoxQuantity.length; i++){
        listBoxQuantity[i].innerHTML = 0;
        listBoxQuantity[i].setAttribute('value', 0);
    }
}

function pushData(){
    // var movieNameTag = document.getElementById('movie-name-text');
    // var roomNameTag = document.getElementById('room-name-text');
    var showtimeTag = document.getElementById('showtime-text');
    var stdTag = document.getElementById('seat-name-text');
    var foodNameTag = document.getElementById('food-name-text');
    var ticketPrice = document.getElementById('ticket-price-text');
    var foodPrice = document.getElementById('food-price-text');
    var totalPrice = document.getElementById('total-price-text');
    var comboPriceContainer = document.querySelector('.price-combo');
    var comboNameContainer = document.querySelector('.food-name-payment');
    $.ajax({
        url: "/booking/payment",
        method: "POST",
        data:{
            listFood: listFood,
            listRealCheckedID: listRealCheckedID,
            showID: showID
        },
        success: function(respone){
            var jsonData = JSON.parse(respone);
            if(jsonData['status'] == 0){
                alert(jsonData['message']);
                window.location.assign('/booking');
            }else{
                if(jsonData['foodPrice'] == 0){
                    comboNameContainer.style.display = "none";
                    comboPriceContainer.style.display = "none";
                }else{
                    comboNameContainer.style.display = "flex";
                    comboPriceContainer.style.display = "flex";
                    foodNameTag.innerHTML =  jsonData['foodBooked'];
                    foodPrice.innerHTML = formatNumberToMoney(jsonData['foodPrice']);
                }
                showtimeTag.innerHTML = jsonData['startTime'];
                stdTag.innerHTML = jsonData['STDlist'];
                ticketPrice.innerHTML = formatNumberToMoney(jsonData['ticketPrice']);
                totalPrice.innerHTML = formatNumberToMoney(jsonData['totalPrice']);
            }
            
        }
    })
}

function pushDataToBooking(methodPay){
    $.ajax({
        url: '/payment',
        method: 'POST',
        data:{
            listFood: listFood,
            listRealCheckedID: listRealCheckedID,
            showID: showID,
            methodPay: methodPay
        }, 
        success: function(respone){
            var jsonData = JSON.parse(respone);
            if(jsonData['status'] == 1){
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: 'Đã đặt vé thành công!',
                })
                window.location.assign('/profile');
            }else{
                Swal.fire({
                    icon: 'fail',
                    title: 'Thất bại',
                    text: jsonData['message'],
                })
                alert(jsonData['message']);
                window.location.assign('/booking');
            }
        },
        error: function(respone){
            alert('Something went wrong! Back to home page');
            window.location.assign('/');
        }

    })
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