
let foodID;
$("#btn-add").click(function () {
    document.getElementById("formAdd").reset();
    document.getElementById("container-button").innerHTML = `   
            <button class="btn btn-primary button" style="width:30%" id="btn-comfirm"> Xác nhận </button>
            <button class="btn btn-danger button" style="width:30%" id="btn-exit"> Hủy </button>`
    btnAddEvent();
    btnExitEvent();
    $("#imageFood").removeAttr("src")
  
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
            <button class="btn btn-primary button" style="width:30%" id="btn-edit"> Xác nhận </button>
            <button class="btn btn-danger button" style="width:30%" id="btn-exit"> Hủy </button>`
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
            $("#imageFood").attr("src", res.foodImage)
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
        cancelButtonColor: '#d33000',
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
function checkValidateFood(){
    var titleError = "";
    var isError;
    var foodName = $("#food-name").val()
    var foodPrice = $("#price").val()
    var foodDescription = $("#descrip").val()
    var foodDescriptionNoSpace = foodDescription.replace(/ /g, '').length;
    var foodNameNoSpace = foodName.replace(/ /g, '').length;
    var image = $("#image").val();
    if (foodName == "" || foodPrice == ""  || foodDescription == "" ||image == "") {
        Swal.fire({
            icon: 'error',
            title: 'Vui lòng nhập đủ các trường dữ liệu',
            text: 'Something went wrong!',
        })
        return false;
    }
    if (foodNameNoSpace < 3) {
        titleError += "Tên thức ăn phải > 3 kí tự <br>"
        isError = 1;
    }
    if (foodPrice < 0) {
        titleError += "Giá tiền phải > 0 <br>"
        isError = 1;
    }
    if (foodDescriptionNoSpace < 10) {
        titleError += " Mô tả phải lớn hơn 10 kí tự <br>"
        isError = 1;
    }
    if(isError ==1)
    {
        Swal.fire({
            icon: 'error',
            title: titleError,
            text: 'Something went wrong!',
        })
        return false;
    }
    return true

}
function btnAddEvent() {
    $("#btn-comfirm").click(function (e) {
        e.preventDefault();
       if(!checkValidateFood()){
        return;
       }
        else {
            var formData = new FormData();
            $image =$("#image")[0].files[0],
            $foodName =$("#food-name").val(),
            $price =$("#price").val(),
            $descrip =$("#descrip").val(),
            formData.append('image',  $image);
            formData.append('foodName', $foodName);
            formData.append('price', $price);
            formData.append('descrip', $descrip);
            $.ajax({
                dataType: 'json',
                url: "adminFood/insert",
                method: "POST",
                processData: false,
                mimeType: "multipart/form-data",
                contentType: false,
                data: formData,
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



function btnEditEvent() {
    $("#btn-edit").click(function (e) {
        e.preventDefault();
        var titleError = "";
        var isError;
        var foodName = $("#food-name").val()
        var foodPrice = $("#price").val()
        var foodDescription = $("#descrip").val()
        var foodDescriptionNoSpace = foodDescription.replace(/ /g, '').length;
        var foodNameNoSpace = foodName.replace(/ /g, '').length;
        if (foodName == "" || foodPrice == ""  || foodDescription == "" ) {
            Swal.fire({
                icon: 'error',
                title: 'Vui lòng nhập đủ các trường dữ liệu',
                text: 'Something went wrong!',
            })
            return false;
        }
        if (foodNameNoSpace < 3) {
            titleError += "Tên thức ăn phải > 3 kí tự <br>"
            isError = 1;
        }
        if (foodPrice < 0) {
            titleError += "Giá tiền phải > 0 <br>"
            isError = 1;
        }
        if (foodDescriptionNoSpace < 10) {
            titleError += " Mô tả phải lớn hơn 10 kí tự <br>"
            isError = 1;
        }
        if(isError ==1)
        {
            Swal.fire({
                icon: 'error',
                title: titleError,
                text: 'Something went wrong!',
            })
            return false;
        }
        else {
            var formData = new FormData();
            $image =$("#image")[0].files[0],
            $foodName =$("#food-name").val(),
            $price =$("#price").val(),
            $descrip =$("#descrip").val(),
            
            formData.append('foodID', foodID);
            formData.append('image',  $image);
            formData.append('foodName', $foodName);
            formData.append('price', $price);
            formData.append('descrip', $descrip);
            $.ajax({

                dataType: 'json',
                url: "adminFood/update",
                method: "POST",
                processData: false,
                mimeType: "multipart/form-data",
                contentType: false,
                data: formData,
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

// const images = document.querySelectorAll('img');

// images.forEach(img => {
//     img.addEventListener('error', function handleError() {
//         const defaultImage ="assets/imgFood/cocacola.png";
//         img.setAttribute("src", defaultImage)
//     });
// });
function previewImage(inputId, imgId) {
    var input = document.getElementById(inputId);
    var img = document.getElementById(imgId);
  
    if (input.files && input.files[0]) {
      var reader = new FileReader();
  
      reader.onload = function(e) {
        img.setAttribute("src", e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
    }
  }