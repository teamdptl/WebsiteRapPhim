// let handlesSlider = document.getElementById('range-slider');
//
// noUiSlider.create(handlesSlider, {
//     start: [0, 19],
//     range: {
//         'min': [0],
//         'max': [19]
//     }
// });

const sendRequest = (context) =>{
    $.ajax({
        url: "/movieTest",
        method: 'POST',
        data: context,
        success: function (res) {

        },
        fail: function (res) {

        }
    })
}
const obj = {
    text: "",
    category: 0,
    minAge: 0,
    maxAge: 99,
    ratingMin: 0,
    ratingMax: 10,
    currentPage: 1
}

let context = new Proxy(obj, {
    set(target, p, value, receiver) {
        console.log(context);
    }
})

// Search handle
const textBox = $("#btn-search-text")[0];
textBox.addEventListener("click", function(){
    context.text = $("#text-box").val();
})

// Category handle
const category = $("#category-select")[0];
category.addEventListener("change", function(){
    context.category = category.value;
})

// Rating handle
const rating = $("#rating-select")[0];
rating.addEventListener("change", function(){
    const ratingValue = JSON.parse(rating.value);
    context.ratingMin = ratingValue[0] ?? 0;
    context.ratingMax = ratingValue[1] ?? 10;
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

// Page handle
const nextPage = $("#next-page")[0];
const prevPage = $("#prev-page")[0];
const listPages = $(".page-item-custom");
const maxPages = listPages.length;

nextPage.addEventListener("click", function(){
    const preItem = listPages[context.currentPage-1];
    preItem.classList.remove("active");
    context.currentPage = context.currentPage + 1 > maxPages ? maxPages : context.currentPage + 1;
    listPages[context.currentPage-1].classList.add("active");
})

prevPage.addEventListener("click", function(){
    const preItem = listPages[context.currentPage-1];
    preItem.classList.remove("active");
    context.currentPage = context.currentPage - 1  > 0 ? context.currentPage - 1 : 1;
    listPages[context.currentPage-1].classList.add("active");
})

for (let i=0;i<listPages.length;i++) {
    listPages[i].addEventListener("click", function(e){
        if (context.currentPage !== i + 1) {
            const preItem = listPages[context.currentPage - 1];
            preItem.classList.remove("active");
            context.currentPage = i + 1;
            listPages[i].classList.add("active");
        }
    })
}
