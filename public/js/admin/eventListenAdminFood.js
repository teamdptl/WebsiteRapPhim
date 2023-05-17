let foodID;
$("#btn-add").click(function () {
    document.getElementById("formAdd").reset();
    document.getElementById("container-button").innerHTML = `   
            <button class="btn btn-primary button" id="btn-comfirm"> Xác nhận </button>
            <button class="btn btn-danger button" id="btn-exit"> Hủy </button>`
    btnAddEvent();
    btnExitEvent();
    document.getElementById("formAdd").style.display = "flex"

})
function btnExitEvent() {
    $("#btn-exit").click(function (e) {
        e.preventDefault();
        document.getElementById("formAdd").style.display = "none"
    });
}
$(".edit").click(function (e) {
    document.getElementById("formAdd").style.display = "flex";
    document.getElementById("container-button").innerHTML = `   
            <button class="btn btn-primary button" id="btn-edit"> Xác nhận </button>
            <button class="btn btn-danger button" id="btn-exit"> Hủy </button>`
    foodID = e.target.getAttribute("foodId");
    document.getElementById("formAdd").reset();
    btnEditEvent();
    btnExitEvent();
    $.ajax({
        url: "adminFood/edit",
        method: "GET",
        dataType: "json",
        data: {
            foodID: foodID,
        },
        success: function (res) {
            $("#food-name").val(res.foodName)
            // $("#image").val(res.foodImage)
            $("#price").val(res.foodPrice)
            $("#descrip").val(res.foodDescription)
            $("#discountID").val(res.discountID)
        }
    })

});
$(".del").click(function (e) {
    foodID = e.target.getAttribute("foodID");
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "adminFood/del",
                method: "POST",
                dataType: "json",
                data: {
                    foodID: foodID,
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

                }
            })

        }
    })
})
function btnEditEvent() {
    $("#btn-edit").click(function (e) {
        e.preventDefault();
        if ($("#food-name").val() == "" || $("#price").val() == "" || $("#discountID").val() == "" || $("#descrip").val() == "") {
            Swal.fire({
                icon: 'error',
                title: 'Vui lòng nhập đủ các trường dữ liệu',
                text: 'Something went wrong!',
            })
            return;
        }

        else {
            var image = $("#image").val();
            var imageName = image.split("fakepath\\");
            $.ajax({

                dataType: 'json',
                url: "adminFood/update",
                method: "POST",
                data: {
                    foodID: foodID,
                    foodName: $("#food-name").val(),
                    price: $("#price").val(),
                    discountID: $("#discountID").val(),
                    descrip: $("#descrip").val(),
                    image: imageName[1],
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



        if ($("#food-name").val() == "" || $("#image").val() == "" || $("#price").val() == "" || $("#descrip").val() == "") {
            Swal.fire({
                icon: 'error',
                title: 'Vui lòng nhập đủ các trường dữ liệu',
                text: 'Something went wrong!',
            })
            return;
        }
        else {
            var image = $("#image").val();
            var imageName = image.split("fakepath\\");
            $.ajax({
                dataType: 'json',
                url: "adminFood/insert",
                method: "POST",
                data: {
                    foodName: $("#food-name").val(),
                    image: imageName[1],
                    price: $("#price").val(),
                    discountID: $("#discountID").val(),
                    descrip: $("#descrip").val(),
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

const images = document.querySelectorAll('img');

images.forEach(img => {
    img.addEventListener('error', function handleError() {
        const defaultImage ="assets/imgFood/cocacola.png";
        img.setAttribute("src", defaultImage)
    });
});