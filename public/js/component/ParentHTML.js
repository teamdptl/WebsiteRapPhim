class ParentHTML extends HTMLElement{
    constructor(){
        super();
    }

    get(attributeName){
        return this.getAttribute(attributeName);
    }

    connectedCallback(){

    }

    render() {

    }
}