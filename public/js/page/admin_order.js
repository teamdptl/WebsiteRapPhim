$('.row-booking').click((e) => {
    if(e.currentTarget.classList.contains('row-booking') && !e.target.classList.contains('custom-control-input') && !e.target.classList.contains('custom-control-label')){
        viewDetailBooking(e.currentTarget);
    }
    console.log(e);
})

function checkBooking(inp){
    console.log(inp.checked);
    var isPaid = inp.checked;
    var bookingID = inp.id;
    $.ajax({
        url:'/adminDonHang',
        method:'POST',
        data: {
            isPaid:isPaid,
            bookingID: bookingID
        },
        success:function(respone){
            var jsonData = JSON.parse(respone);
            if(jsonData['status'] == 1){
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: jsonData['message'],
                })
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi hệ thống',
                    text: jsonData['message'],
                })
                inp.checked = !isPaid;
            }
        }
    })
}