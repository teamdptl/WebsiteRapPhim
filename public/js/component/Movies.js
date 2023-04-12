class Movies extends ParentHTML {
    

    listMove = [];
    render(){
        let link = this.getAttribute("link");
        let name = this.getAttribute("name");
        let type = this.getAttribute("type");
        let id = this.getAttribute("id");
        var content = `
        <a href="/movies/${id}" style = "color :black;    text-decoration-line: none;">
        <div class="film-component-container-picture" class="">
            <img src="${link}" alt="">
            <div class="film-component-container-age">
                <label>13</label>   
                <label style="font-size:11px">+</label>  
            </div>
        </div>
        <div class="film-component-title">
            <label>
            ${name}
            </label>
        </div>
        <div class="film-component-type">
            <label>
            ${type}
            </label>
        </div>
        </a>
        <div class="film-component-btn" onclick="buyTicket()"><button>
        <i class="fa-regular fa-cart-shopping fa-spin-pulse " "></i>
                MUA VÉ
            </button></div> `
        this.innerHTML = content;
    }
    connectedCallback() {
        this.listMove = JSON.parse(this.get("list"));
        this.removeAttribute("list");
        this.render();  
    }
}
customElements.define('movie-tag', Movies);