let isCurrentAdd = false;
const fetchUserData = () => {
    $.ajax({
        url: '/adminQuanLyTaiKhoan/user',
        data: {
            search: $("#text-box").val()
        },
        success: function (res){
            let data = JSON.parse(res);
            let html = '';
            html = data.map(item=>{
                return `<div class="card d-flex flex-row justify-content-around px-3 py-3 align-items-center rounded user-item">
                    <div style="width: 50px">${item.userID}</div>
                    <div style="width: 150px">${item.fullName}</div>
                    <div style="width: 200px">${item.email}</div>
                    <div style="width: 150px">${item.isActive === true ? "Hoạt động" : "Bị chặn"}</div>
                    <div style="width: 150px">${item.groupName}</div>
                    <div class="dropdown">
                        <div class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class='bx bx-dots-vertical-rounded' ></i>
                        </div>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="openModalEdit('${item.userID}')"><i class='bx bx-edit-alt'></i> Sửa người dùng</a>
                            <a class="dropdown-item" href="#" onclick="openModalDelete('${item.userID}')"><i class='bx bx-trash' ></i> Xóa người dùng</a>
                        </div>
                    </div>
                </div>`
            }).join(" ");
            $("#user-container").html(html);
        }
    })
}
const fetchGroups = () => {
    $.ajax({
        url: '/adminQuanLyTaiKhoan/getAllGroup',
        method: "GET",
        success: function (res){
            let html = ''
            let data = JSON.parse(res);
            html = data.map(item => {
                return `<option value="${item.permissionID}">${item.groupName}</option>`
            }).join(" ");
            $("#userGroup").html(html);
        }
    })
}
const openModalEdit = (userId) => {
    isCurrentAdd = false;
    $("#modalUserTitle").text("Sửa người dùng");
    fetchGroups();
     $.ajax({
        url: "/adminQuanLyTaiKhoan/getUserId",
        method: "GET",
        data: {
            userId: userId
        },
        success: function (res){
            let data = JSON.parse(res);
            $("#userId").val(data.userID);
            $("#fullName").val(data.fullName);
            $("#email").val(data.email);
            $("#password").val(data.userPassword);
            $("#user-active").prop("checked", data.isActive);
            $("#userGroup").val(data.permissionID);
            $("#addUserModal").modal('show');
        }
    })
}
const openModalAdd = () => {
    isCurrentAdd = true;
    fetchGroups();
    $("#modalUserTitle").text("Thêm người dùng");
    $("#fullName").val("");
    $("#email").val("");
    $("#password").val("");
    $("#user-active").prop("checked", true);
    $("#userGroup").val();
    $("#addUserModal").modal('show');
}

const openModalDelete = (userId) => {
    Swal.fire({
        title: 'Bạn có chắc muốn xóa tài khoản không',
        showDenyButton: true,
        confirmButtonText: 'Có',
        denyButtonText: `Không`,
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/adminQuanLyTaiKhoan/deleteUser",
                method: "POST",
                data: {
                    userId: userId,
                },
                success: function (res) {
                    Swal.fire('Xóa thành công người dùng!', '', 'success').then(()=>{
                        location.reload();
                    })
                },
                error: function (){
                    Swal.fire('Không xóa được người dùng!', '', 'error');
                }
            })
        }
    })
}

fetchUserData();

// Event for button search
$("#btn-search-text").click(function (){
    fetchUserData();
});

$("#showPassword").click(function(){
    let type = $("#password").attr("type");
    if (type === "password"){
        $("#password").attr("type", "text");
    } else {
        $("#password").attr("type", "password");
    }
})

$("#btnSave").click(function(){
    let url = '';
    let data = {
        userId: $("#userId").val() ?? 0,
        fullName: $("#fullName").val() ?? "",
        email: $("#email").val() ?? "",
        userPassword: $("#password").val() ?? "",
        isActive: $("#isActive").val() ?? true,
        permission: $("#userGroup").val(),
    }
    if (isCurrentAdd === true){
        url = '/adminQuanLyTaiKhoan/saveUser'
    } else {
        url = '/adminQuanLyTaiKhoan/editUser'
    }
    $.ajax({
        url: url,
        method: "POST",
        data: data,
        success: function (res){
            let data = JSON.parse(res);
            if (data.status){
                location.reload();
            } else {
                Swal.fire('Lỗi!', data.msg, 'error');
            }
        }
    })
})

$("#text-box").on("input", function (e){
    if (!e.target.value){
        fetchUserData();
    }
})

$("#btnAddUser").click(openModalAdd);

const recoveryAccount  = (userId) =>{
    $.ajax({
        url: "/adminQuanLyTaiKhoan/recovery",
        method: "POST",
        data: {
            accountId: userId
        },
        success: function (res) {
            let data = JSON.parse(res);
            if (data.status){
                Swal.fire('Thành công!', 'Người dùng đã được khôi phục', 'success').then(()=>{
                    location.reload();
                })
            }
            else {
                Swal.fire('Lỗi khôi phục người dùng!', '', 'error');
            }
        }
    })
}