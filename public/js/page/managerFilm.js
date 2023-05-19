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
    $(".add-film").click(function(e) {
      e.preventDefault();
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
        let tagID = $("#Add select[name='tagID'] option:selected").val();
        // $('#poster-link-img-add').attr("src","/assets/posterImgMovie/"+$("#poster-link")[0].files[0]);
        // $('#landscape-poster-img-add').attr("src","/assets/landscapeImgMovie/"+$("#landscape-poster")[0].files[0]);

        console.log(tagID);

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

        $("#btnEditFilm").data("movieID", movieID);

      $.ajax({
        url: "/adminQuanLyPhim/getMovieID",
        method: "get",
        mimeType: "multipart/form-data",
        data: {
          movieID: movieID,
        },
        success: function(response){
          let data =  JSON.parse(response);

          
          $.each(data.movie, function(index, movie) {
            $('#name-movie-edit').val(movie.movieName);
            $('#des-movie-edit').val(movie.movieDes);
            $('#poster-link-img-edit').attr("src",movie.posterLink);
            $('#landscape-poster-img-edit').attr("src",movie.landscapePoster);
            $('#trailer-link-edit').val(movie.trailerLink);
            $('#directors-edit').val(movie.movieDirectors);
            $('#actors-edit').val(movie.movieActors);
            $('#during-time-edit').val(movie.duringTime);
            $('#datepicker-edit').val(movie.dateRelease.substring(0, 10));
            $('#language-edit').val(movie.movieLanguage);
            $('#customSwitches-edit').prop('checked', movie.isFeatured);
            });

          
            $("#tagID-edit").val(data.minAge[0].minAge);

            $.each(data.listCategory, function(index, category) {
              $('#Edit #'+category.categoryID).prop('checked', true);
           });

        },
        error: function(err){
          // xử lý khi lỗi
          console.log(err);
      },

      });

      });

      $("#btnEditFilm").click((e) => {
        e.preventDefault();
        var movieID = $(e.target).data("movieID");
        var formData = new FormData();

        let nameMovie = $("#name-movie-edit").val();
        let desMovie = $("#des-movie-edit").val();
        let posterLink = $("#poster-link-edit")[0].files[0];     
        let landscapeLink = $("#landscape-poster-edit")[0].files[0];
        let trailerLink = $("#trailer-link-edit").val();
        let directors = $("#directors-edit").val();
        let actors = $("#actors-edit").val();
        let duringTime = $("#during-time-edit").val();
        let datePicker = $("#datepicker-edit").val();
        let language = $("#language-edit").val();
        let customSwitches = $("#customSwitches-edit").is(':checked') ? '1' : '0';
        let minAge = $("#tagID-edit").val(); //chỉ lụm được minAge, cần phải sửa qua tagid

        formData.append('movieID', movieID);
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
        formData.append('minAge', minAge);

        var checkedInputs = $(".checkboxCategoryEdit");
        var checkedIds = [];
        checkedInputs.each(function() {
          if ($(this).prop('checked')) {
            checkedIds.push($(this).attr("id"));
          }
        });

        console.log(checkedIds);

        formData.append('checkedIds', checkedIds);


          $.ajax({
            url: "/adminQuanLyPhim/SuaPhim",
            method: "post",
            processData: false,
            contentType: false,
            mimeType: "multipart/form-data",
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

     

});


// function fetchSearchResults() {
//   var searchType = $('select[name="searchType"]').val();
//   var searchValue = $('input[name="searchValue"]').val();

  

//   console.log(searchType);
//   console.log(searchValue);

//   $.ajax({
//       url: '/adminQuanLyPhim',
//       method: 'GET',
//       data: {
//           searchType: searchType,
//           searchValue: searchValue
//       },
//       success: function(response) {
//           $('#searchResults').html(response);
//       },
//       error: function() {
//           // Xử lý lỗi nếu có
//       }
//   });
// }

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