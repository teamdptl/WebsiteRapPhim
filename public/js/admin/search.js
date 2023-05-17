let optionSearch = 0 ;
    $(document).ready(function(){
            $(".default-option").click(function(){
                $(".dropdown ul").toggleClass("active");
            });

            $(".dropdown ul li").click(function(){
                var text = $(this).text();
                optionSearch = $(this).attr("id");
                $(".default-option").text(text);
                $(".dropdown ul").removeClass("active");
            });
        });
function Search() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("inputSearch");
    filter = input.value.toUpperCase();
    table = document.getElementById("table");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[optionSearch];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
function SearchMovie() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("inputSearchMovie");
    filter = input.value.toUpperCase();
    table = document.getElementById("tableMovie");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

