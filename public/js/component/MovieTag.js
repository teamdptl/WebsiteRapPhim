class MovieTag extends ParentHTML{
    name = "";
    link = "";
    category = [];
    tag = "18+";
    id = ""
    render() {
        let html = `
            <a class="movie-item d-flex flex-column" href="/movies/${this.id}">
                    <div class="img-container position-relative">
                        <img class="custom-round" src="${this.link}" style="width: 100%">
                        <div class="position-absolute bg-white text-danger font-weight-bold px-1 rounded" style="right: 10px; top: 10px; font-size: 0.8rem">
                            ${this.tag}
                        </div>
                    </div>
                    <h5 class="mt-3 mb-0 text-movie-hidden">${this.name}</h5>
                    <p class="text-muted text-movie-hidden">${this.concatCategory()}</p>
                    <button class="btn text-white font-weight custom-round w-100 mt-auto" style="background-color: #E64545"">
                        <i class="bi bi-cart3 mr-1"></i>
                        Mua vé
                    </button>
            </a>
        `;
        this.outerHTML = html;
    }

    connectedCallback() {
        this.link = this.get("link");
        this.name = this.get("name");
        this.category = JSON.parse(this.get("category"));
        this.id = this.get("id");
        this.tag = this.get("tag");
        this.render();
    }

    concatCategory() {
        let text = "";
        if (this.category){
            text = this.category[0];
            for (let i =1; i<this.category.length; i++){
                text += `, ${this.category[i].toLowerCase()}`
            }
        }
        return text;
    }
}

customElements.define('movie-tag', MovieTag);