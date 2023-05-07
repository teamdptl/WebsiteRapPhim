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
    return `<ul class="pagination">
            <li id="prev-page" class="page-item">
                <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            ${html}
            <li id="next-page" class="page-item">
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
        return `<movie-tag id="${movie.movieID}" link="${movie.posterLink}" category='${movie.category}' name="${movie.movieName}" tag="${movie.tag.tagName}"></movie-tag>`;
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
            res = JSON.parse(res);
            $("#movie-container").html(createMovieItems(res.list));
            $("#pagination").html(createPagination(res["maxPage"], res["activePage"]));
            if (res["maxPage"]>1){
                handlePage();
            }
            $("#loadingScreen").css("display", "none");
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
    ratingMin: 0,
    ratingMax: 10,
    currentPage: 1,
    futureMovie: 0
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

// Rating handle
const rating = $("#rating-select")[0];
rating.addEventListener("change", function(){
    const ratingValue = JSON.parse(rating.value);
    context.ratingMin = ratingValue[0] ?? 0;
    context.ratingMax = ratingValue[1] ?? 10;
    context.currentPage = 1;
    sendRequest(context);
})

// Age handle
const ageMin = $("#age-pick-min")[0];
const ageMax = $("#age-pick-max")[0];
ageMin.addEventListener("input", function(){
    context.minAge = ageMin.value;
})

ageMax.addEventListener("input", function(){
    context.maxAge = ageMax.value;
})

// Date Release
const movieNowBtn = $("#movie-now")[0];
const movieFutureBtn = $("#movie-future")[0];
movieNowBtn.addEventListener("click", function(){
    context.futureMovie = 0;
    context.currentPage = 1;
    movieNowBtn.classList.add("active");
    movieFutureBtn.classList.remove("active");
    sendRequest(context);
    $("#text-title").text("Các bộ phim đang chiếu")
})

movieFutureBtn.addEventListener("click", function(){
    context.futureMovie = 1;
    context.currentPage = 1;
    movieNowBtn.classList.remove("active");
    movieFutureBtn.classList.add("active");
    sendRequest(context);
    $("#text-title").text("Các bộ phim sắp chiếu")
})

// Page handle
const handlePage = () => {
    const nextPage = $("#next-page")[0];
    const prevPage = $("#prev-page")[0];
    const listPages = $(".page-item-custom");
    const maxPages = listPages.length;

    nextPage.addEventListener("click", function(){
        const preItem = listPages[context.currentPage-1];
        preItem.classList.remove("active");
        context.currentPage = context.currentPage + 1 > maxPages ? maxPages : context.currentPage + 1;
        listPages[context.currentPage-1].classList.add("active");
        sendRequest(context);
    })

    prevPage.addEventListener("click", function(){
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

handlePage();

