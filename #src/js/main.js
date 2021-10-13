@@include('webp.js')
@@include('jquery.pagepiling.js')

$(document).ready(function() {
    $('#pagepiling').pagepiling( {
            sectionSelector: '.slide-section',
        });
    }
);
const body = document.querySelector("body")
const main = document.querySelector(".main")
const pagepiling = document.querySelector("#pagepiling")
const burger = document.querySelector(".burger")
const menu = document.querySelector('.header__menu')
const langMenu = document.querySelector('.header__lang')
const langList = document.querySelector('.active__lang ul')



burger.addEventListener('click',()=>{
    burger.classList.toggle('burger-active')
    if(burger.className.includes("burger-active")){
        menu.classList.add("header__menu-active")
        langMenu.style.display='block'
    }else{
        menu.classList.remove("header__menu-active")
        langMenu.style.display='none'
    }
})

langMenu.addEventListener('click',()=>{
    langList.classList.toggle('visble__lang')
})

main.contains(pagepiling) && body.classList.add("body-overflow")

const swiper = new Swiper('.model__slider', {
    wrapperClass: "model__wrapper",
    slideClass:"model__img",
    slidesPerView: "auto",
    spaceBetween: 13,
});
