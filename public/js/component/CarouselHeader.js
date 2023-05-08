class CarouselHeader extends ParentHTML{
    listMove = [];

    render(){
        let htmlContent = `<div class="owl-carousel"></div>`;
        if (this.listMove.length){
            let itemsHTML = this.listMove.map(item=>{
                return `<a href="/movies/${item.movieID}" class="position-relative"> 
                            <img src="https://image.tmdb.org/t/p/original/${item.landscapePoster}" class="img" alt="Image for movie">
                            <div class="position-absolute" 
                            style="bottom: 0; left: 0; right: 0; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px; background-color: #09090945!important;">
                                <h5 class="text-white px-5 pt-3">${item.movieName}</h5>
                                <p class="px-5 pb-2 text-light">${this.concatCategory(item.categoryNames)}</p>
                            </div>
                        </a>`
            }).join(" ");
            htmlContent = `
                    <div class="carousel-container">
                        <div class="d-flex justify-content-center algin-items-center pt-4 pb-1">
                            <span class="text-uppercase py-0 hot-title text-white text-center">Phim nổi bật trong tuần</span>
                        </div>
                        <div class="owl-carousel pb-4">
                            ${itemsHTML}
                        </div>
                    </div>
                `
        }
        this.innerHTML = htmlContent;
    }

    connectedCallback(){
        this.listMove = JSON.parse(this.get("list"));
        this.removeAttribute("list");
        this.render();
    }

    concatCategory(category) {
        let text = "";
        if (category){
            text = category[0];
            for (let i =1; i<category.length; i++){
                text += `, ${category[i].toLowerCase()}`
            }
        }
        return text;
    }
}

customElements.define('carousel-header', CarouselHeader);