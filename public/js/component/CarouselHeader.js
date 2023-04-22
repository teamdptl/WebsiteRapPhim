class CarouselHeader extends ParentHTML{
    listMove = [];

    render(){
        let htmlContent = `<div class="owl-carousel"></div>`;
        if (this.listMove.length){
            let itemsHTML = this.listMove.map(item=>{
                return `<div> 
                            <img src="${item.link}" class="img" alt="Image for movie">
                        </div>`
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
}

customElements.define('carousel-header', CarouselHeader);