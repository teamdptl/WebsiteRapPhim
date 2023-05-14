$(document).ready(function(){
    $(".default-option").click(function(){
        $(".dropdown ul").toggleClass("active");
    });

    $(".dropdown ul li").click(function(){
        var text = $(this).text();
        $(".default-option").text(text);
        $(".dropdown ul").removeClass("active");
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
        // console.log(posterLink);
        
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
          method: "post",
          processData: false,
          mimeType: "multipart/form-data",
          contentType: false,
          data: formData,
             
          success: function(response){
              // xử lý khi thành công
              let data =  JSON.parse(response);
              if(data.status == 1){
                location.reload();
              }
              if(data.status == 0){
                Swal.fire({
                  icon: 'error',
                  title: 'Vui lòng nhập lại',
                  text: data.message,
              });
              }
        },
          error: function(jqXHR, textStatus, errorThrown){
              // xử lý khi lỗi
              console.log(textStatus, errorThrown);
          },
      });
      


    });

    $('.deleteFilm').click(function() {
      // Sử dụng toggle để hiển thị/ẩn thị modal-delete
      var movieID = $(this).attr("id"); // Lấy ID từ thuộc tính id của phần tử HTML
      $('.modal-delete').toggle();
      $(".btn-danger").data("movieID", movieID);
      
  });
  

      $(".btn-danger").click((e) => {
        var movieID = $(e.target).data("movieID");

        var formData = new FormData();
        formData.append('movieID', movieID);
        $.ajax({
          url: "/adminQuanLyPhim/XoaPhim",
            method: "post",
            processData: false,
            mimeType: "multipart/form-data",
            contentType: false,
            data: formData,
            success: function(response){
              location.reload();
            },
            error: function(err){
              // xử lý khi lỗi
              console.log(err);
          },
     
  
        });
      
      });
  
      $('.edit-film').click(function() {
        $("#Edit").toggle();
        var movieID = $(this).attr("id");
        console.log(movieID);
        console.log("nè ba" + movieID);


        // $("#btnEditFilm").data("movieID", movieID);


      $.ajax({
        url: "/adminQuanLyPhim/getMovieID",
        method: "get",
        processData: false,
       
        data: {
          movieID: movieID,
        },
        success: function(response){
          console.log(response);
        },
        error: function(err){
          // xử lý khi lỗi
          console.log(err);
      },

      });

      });

      $("#btnEditFilm").click((e) => {
        e.preventDefault();
        // var movieID = $(e.target).data("movieID");

      });
    


});


