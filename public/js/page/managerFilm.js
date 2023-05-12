$(document).ready(function(){
    $(".default-option").click(function(){
        $(".dropdown ul").toggleClass("active");
    });

    $(".dropdown ul li").click(function(){
        var text = $(this).text();
        $(".default-option").text(text);
        $(".dropdown ul").removeClass("active");
    });

    
    $('.deleteFilm').click(function() {
        // Sử dụng toggle để hiển thị/ẩn thị modal-delete
        $('.modal-delete').toggle();
    });

    // Lắng nghe sự kiện khi click vào nút đóng (x) hoặc nút Đóng
    $('.close, .btn-secondary').click(function() {
        // Sử dụng toggle để ẩn thị modal-delete
        $('.modal-delete').toggle();
    });
  
    //modal addFilm
    $(".add-film").click(function() {
      $("#Add").toggle();
    });

    $('.edit-film').click(function() {
      $("#Edit").toggle();
      console.log("testing");
    });

    // Khi click bên ngoài modal, đóng modal
    $(window).on('click', function(event) {
        if ($(event.target).is('.modal')) {
          $(".modal").hide();
        };

        if ( $(event.target).is('.modalAddFilm')) {
          $(".containerFilmAdd").hide();
        }
    });

    
    $("#btnAddFilm").click((e) => {
        e.preventDefault();
        var formData = new FormData();

        let nameMovie = $("#name-movie-add").val();
        let desMovie = $("#des-movie").val();
        let posterLink = $("#poster-link")[0].files[0];
        let landscapeLink = $("#landscape-poster")[0].files[0];
        let trailerLink = $("#trailer-link").val();
        let directors = $("#directors").val();
        let actors = $("#actors").val();
        let duringTime = $("#during-time").val();
        let datePicker = $("#datepicker").val();
        let language = $("#language").val();
        let customSwitches = $("#customSwitches").is(':checked') ? '1' : '0';
        let tagID = $("select[name='tagID'] option:selected").val();
       

        formData.append('nameMovie', nameMovie);
        formData.append('desMovie', desMovie);
        formData.append('posterLink', posterLink);
        formData.append('landscapeLink', landscapeLink);
        formData.append('trailerLink', trailerLink);
        formData.append('directors', directors);
        formData.append('actors', actors);
        formData.append('duringTime', duringTime);
        formData.append('datePicker', datePicker);
        formData.append('language', language);
        formData.append('customSwitches', customSwitches);
        formData.append('tagID', tagID);


        //array checkbox have value="checked"
        var checkedInputs = $(".checkboxCategoryAdd");
        var checkedIds = [];
        checkedInputs.each(function() {
          if ($(this).prop('checked')) {
            checkedIds.push($(this).attr("id"));
          }
        });

        formData.append('checkedIds', checkedIds);

        // console.log(checkedIds);

        $.ajax({
          url: "/adminQuanLyPhim/ThemPhim",
          type: "post",
          processData: false,
          mimeType: "multipart/form-data",
          contentType: false,
          data: formData,
             
          
          success: function(data){
              // xử lý khi thành công
              let result = JSON.parse(data);
              // console.log(data);
          },
          error: function(jqXHR, textStatus, errorThrown){
              // xử lý khi lỗi
              console.log(textStatus, errorThrown);
          }
      });
     

    });

   

    
    


});


