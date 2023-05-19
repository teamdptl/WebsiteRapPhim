// let handlesSlider = document.getElementById('range-slider');
//
// noUiSlider.create(handlesSlider, {
//     start: [0, 19],
//     range: {
//         'min': [0],
//         'max': [19]
//     }
// });

const createPagination = (number, activeNumber) => {
    if (number < 2) return ``;
    let html = ``;
    for (let i = 1; i <= number; i++){
        if (activeNumber === i)
            html += `<li class="page-item page-item-custom active"><a class="page-link" href="#">${i}</a></li>`
        else html += `<li class="page-item page-item-custom"><a class="page-link" href="#">${i}</a></li>`
    }
    let prePageClass =  activeNumber === 1 ? "disabled" : "";
    let nextPageClass = activeNumber === number ? "disabled" : "";
    return `<ul class="pagination">
            <li id="prev-page" class="page-item ${prePageClass}">
                <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            ${html}
            <li id="next-page" class="page-item ${nextPageClass}">
                <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
         </ul>`;
}

const createMovieItems = (listData) => {
    if (!listData.length){
        return "";
    }
    let html = listData.map((movie) => {
        return `<movie-tag id="${movie.movieID}" link="${movie.posterLink}" category='${movie.category}' name="${movie.movieName}" tag="${movie.tagName}"></movie-tag>`;
    })
    return html.join(" ");
}

const sendRequest = (context) =>{
    $("#loadingScreen").css("display", "block");
    $.ajax({
        url: "/moviesSearch",
        method: 'GET',
        data: context,
        success: function (res) {
            try {
                res = JSON.parse(res);
                $("#movie-container").html(createMovieItems(res.list));
                $("#pagination").html(createPagination(res["maxPage"], res["activePage"]));
                if (res["maxPage"]>1){
                    handlePage();
                }
            } catch (e){

            } finally {
                $("#loadingScreen").css("display", "none");
            }

        },
        fail: function (res) {
            console.log(res);
            $("#loadingScreen").css("display", "none");
        }
    })
}

const context = {
    text: "",
    category: 0,
    minAge: 0,
    maxAge: 99,
    cinema: 0,
    currentPage: 1,
    futureMovie: location.hash === "#futureMovie" ? 1 : 0,
    sortBy: 0,
}

// Search handle
const textBox = $("#btn-search-text")[0];
textBox.addEventListener("click", function(){
    context.text = $("#text-box").val();
    context.currentPage = 1;
    sendRequest(context);
})

// Category handle
const category = $("#category-select")[0];
category.addEventListener("change", function(){
    context.category = category.value;
    context.currentPage = 1;
    sendRequest(context);
})

// Cinema handle
const cinema = $("#cinema-select")[0];
cinema.addEventListener("change", function(){
    context.cinema = cinema.value
    context.currentPage = 1;
    sendRequest(context);
})

// Age handle
const ageMin = $("#age-pick-min")[0];
const ageMax = $("#age-pick-max")[0];
ageMin.addEventListener("input", function(){
    context.minAge = ageMin.value;
    context.currentPage = 1;
    if (ageMin.value === "")
        context.minAge = 0;
})

ageMax.addEventListener("input", function(){
    context.maxAge = ageMax.value;
    context.currentPage = 1;
    if (ageMax.value === "")
        context.maxAge = 99
})

const sortBtn = $("#sort-select")[0];
sortBtn.addEventListener("change", function(){
    context.sortBy = sortBtn.value;
    context.currentPage = 1;
    sendRequest(context);
})

// Date Release
const movieNowBtn = $("#movie-now")[0];
const movieFutureBtn = $("#movie-future")[0];

const movieNowHandler = () =>{
    context.futureMovie = 0;
    context.currentPage = 1;
    movieNowBtn.classList.add("active");
    movieFutureBtn.classList.remove("active");
    location.hash = "";
    sendRequest(context);
    $("#text-title").text("Các bộ phim đang chiếu")
}
movieNowBtn.addEventListener("click", movieNowHandler);

const futureMovieHandler = () =>{
    context.futureMovie = 1;
    context.currentPage = 1;
    movieNowBtn.classList.remove("active");
    movieFutureBtn.classList.add("active");
    location.hash = "#futureMovie";
    sendRequest(context);
    $("#text-title").text("Các bộ phim sắp chiếu");
}

movieFutureBtn.addEventListener("click", futureMovieHandler);

if (context.futureMovie){
    futureMovieHandler();
} else {
    movieNowHandler();
}

// Page handle
const handlePage = () => {
    const nextPage = $("#next-page")[0];
    const prevPage = $("#prev-page")[0];
    const listPages = $(".page-item-custom");
    const maxPages = listPages.length;

    nextPage.addEventListener("click", function(){
        if (nextPage.classList.contains("disabled"))
            return;

        const preItem = listPages[context.currentPage-1];
        preItem.classList.remove("active");
        context.currentPage = context.currentPage + 1 > maxPages ? maxPages : context.currentPage + 1;
        listPages[context.currentPage-1].classList.add("active");
        sendRequest(context);
    })

    prevPage.addEventListener("click", function(){
        if (prevPage.classList.contains("disabled"))
            return;

        const preItem = listPages[context.currentPage-1];
        preItem.classList.remove("active");
        context.currentPage = context.currentPage - 1  > 0 ? context.currentPage - 1 : 1;
        listPages[context.currentPage-1].classList.add("active");
        sendRequest(context);
    })

    for (let i=0;i<listPages.length;i++) {
        listPages[i].addEventListener("click", function(e){
            if (context.currentPage !== i + 1) {
                const preItem = listPages[context.currentPage - 1];
                preItem.classList.remove("active");
                context.currentPage = i + 1;
                listPages[i].classList.add("active");
                sendRequest(context);
            }
        })
    }
}

// handlePage();

