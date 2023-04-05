class Carousel extends ParentHTML {
    currentIndex = 0;
    list = [
        {
            id: 0,
            link: "https://cdnmedia.baotintuc.vn/Upload/DmtgOUlHWBO5POIHzIwr1A/files/2022/11/07/Black-Adam-07112022.jpg",
            title: "Hello world",
            description: "Hello world",
        },
        {
            id: 1,
            link: "https://talkfirst.vn/wp-content/uploads/2022/06/describe-your-favorite-movie-avengers-endgame-1024x576.jpg",
            title: "Another text",
            description: "Hello world",
        },
        {
            id: 2,
            link: "https://bloganchoi.com/wp-content/uploads/2023/02/demon-slayer-season-3-release-date.jpg",
            title: "Other Text",
            description: "Hello world",
        }
    ];
    tempList = [];

    render(){
        let htmlContent = '';
        if (this.list.length === 0){
            this.innerHTML = htmlContent;
            return;
        }

        const nextItem = this.list[this.getNext(this.currentIndex)];
        const preItem = this.list[this.getPrev(this.currentIndex)];
        const currentItem = this.list[this.currentIndex];

        htmlContent += `
            <div class="carousel">
            
            <h3 class="text-center py-3 text-white">Phim nổi bật trong tuần</h3>
            <div id="slider" class="slider pb-5">
                <div id="slide-${preItem.id}" class="slide slide-left">
                    <img src="${preItem.link}" alt="${preItem.title}">
                </div>
                <div id="slide-${currentItem.id}" class="slide active">
                    <img src="${currentItem.link}" alt="${currentItem.title}">
                </div>
                <div id="slide-${nextItem.id}" class="slide slide-right">
                    <img src="${nextItem.link}" alt="${nextItem.title}">
                </div>
            </div>
            <div id="btn-next" class="arrow-icon arrow-right">
                 <i class="fa-solid fa-angle-right"></i>
            </div>
            <div id="btn-prev" class="arrow-icon arrow-left">
                 <i class="fa-solid fa-angle-left"></i>
            </div>
            
            </div>
        `;

        this.innerHTML = htmlContent;
        this.addEventForChild();
    }

    addEventForChild(){
        const btnNext = document.querySelector("#btn-next");
        const btnPrev = document.querySelector("#btn-prev");

        btnNext.addEventListener("click", (e)=>{
            this.addNextChild();
        })

        btnPrev.addEventListener("click", (e)=>{
            this.addPreviousChild();
        })
    }

    addNextChild(){
        const slider = document.querySelector("#slider");
        // Remove first node
        slider.removeChild(slider.firstElementChild);

        // Change current node
        const changeNode = document.querySelector(`#slide-${this.currentIndex}`);
        changeNode.classList.remove("active");
        changeNode.classList.add("slide-left");

        // Set active node
        const activeNode = document.querySelector(`#slide-${this.getNext(this.currentIndex)}`);
        activeNode.classList.remove("slide-right");
        activeNode.classList.add("active");

        this.currentIndex = this.getNext(this.currentIndex);

        // Append new node
        const nextItem = this.list[this.getNext(this.currentIndex)];
        // const html = `
        //     <div id="slide-${nextItem.id}" class="slide slide-right">
        //         <img src="${nextItem.link}" alt="${nextItem.title}">
        //     </div>
        // `;

        const lastItem = document.createElement('div');
        lastItem.setAttribute("id", `slide-${nextItem.id}`);
        lastItem.setAttribute("class", "slide slide-right");
        lastItem.innerHTML = `<img src="${nextItem.link}" alt="${nextItem.title}">`;
        slider.append(lastItem);
    }

    addPreviousChild(){
        const slider = document.querySelector("#slider");
        // Remove last node
        slider.removeChild(slider.lastElementChild);

        // Change current node
        const changeNode = document.querySelector(`#slide-${this.currentIndex}`);
        changeNode.classList.remove("active");
        changeNode.classList.add("slide-right");

        // Set active node
        console.log(`#slide-${this.getPrev(this.currentIndex)}`);
        const activeNode = document.querySelector(`#slide-${this.getPrev(this.currentIndex)}`);
        activeNode.classList.remove("slide-left");
        activeNode.classList.add("active");

        this.currentIndex = this.getPrev(this.currentIndex);

        // Append new node
        const prevItem = this.list[this.getPrev(this.currentIndex)];
        // const html = `
        //     <div id="slide-${prevItem.id}" class="slide slide-left">
        //         <img src="${prevItem.link}" alt="${prevItem.title}">
        //     </div>
        // `;
        const headItem = document.createElement('div');
        headItem.setAttribute("id", `slide-${prevItem.id}`);
        headItem.setAttribute("class", "slide slide-left");
        headItem.innerHTML = `<img src="${prevItem.link}" alt="${prevItem.title}">`;
        slider.prepend(headItem);
    }

    getPrev = (index) => {
        return index - 1 >= 0 ? index - 1 : this.list.length - 1;
    }

    getNext = (index) => {
        return index + 1 >= this.list.length ? 0 : index + 1;
    }

    connectedCallback(){
        // this.list = );
        // console.log(this.get("list")));
        this.tempList = JSON.parse(this.get("list"));
        this.removeAttribute("list");
        console.log(this.tempList);
        this.render();
    }
}

customElements.define('movie-carousel', Carousel);