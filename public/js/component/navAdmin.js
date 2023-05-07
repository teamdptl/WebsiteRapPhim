let sidebar = document.querySelector(".sidebar");
let closeBtn = document.querySelector("#btn");
const navItems = document.querySelectorAll('.nav-list li a');

navItems.forEach((item) => {
  item.addEventListener('click', () => {
    // remove selected class from all items
    navItems.forEach((navItem) => {
      navItem.classList.remove('selected');
    });

    // add selected class to clicked item
    item.classList.add('selected');
  });
});




closeBtn.addEventListener("click", ()=>{
  sidebar.classList.toggle("open");
  menuBtnChange();//calling the function(optional)
});

// following are the code to change sidebar button(optional)
function menuBtnChange() {
 if(sidebar.classList.contains("open")){
   closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");//replacing the iocns class
 }else {
   closeBtn.classList.replace("bx-menu-alt-right","bx-menu");//replacing the iocns class
 }
}