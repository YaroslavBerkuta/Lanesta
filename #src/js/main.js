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


const map = document.getElementById('map');

function initMap() {
  map = new google.maps.Map(map, {
    center: { lat: 49.41832849437991, lng: 26.980854698857758 },
    mapId: "1be87eee1d42f34d",
    zoom: 5,
  });
}


$.ajax('https://novias1986.bitrix24.ua/rest/1/z9lgewgqaftmtmxt/crm.contact.list.json?SELECT[]=NAME&SELECT[]=LAST_NAME&SELECT[]=EMAIL', {
    success: (data) => { 
        console.log(data)
     },
    error: (err) => { /* обробка помилки */}
});