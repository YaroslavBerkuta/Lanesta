const body = document.querySelector("body");
const main = document.querySelector(".main");
const pagepiling = document.querySelector("#pagepiling");
const burger = document.querySelector(".burger");
const menu = document.querySelector(".header__menu");
const langMenu = document.querySelector(".header__lang");
const langList = document.querySelector(".active__lang ul");

burger.addEventListener("click", () => {
  burger.classList.toggle("burger-active");
  if (burger.className.includes("burger-active")) {
    menu.classList.add("header__menu-active");
  } else {
    menu.classList.remove("header__menu-active");
  }
});

langMenu.addEventListener("click", () => {
  langList.classList.toggle("visble__lang");
});
function blogSlider() {
  const blogSwiper = new Swiper(".blog__slider", {
    wrapperClass: "blog__wrapper",
    slideClass: "blog__item",
    slidesPerView: "auto",
    spaceBetween: 13,
  });
}

const swiper = new Swiper(".model__slider", {
  wrapperClass: "model__wrapper",
  slideClass: "model__img",
  slidesPerView: "auto",
  spaceBetween: 13,
});
const videoSlider = new Swiper(".video__slider", {
  wrapperClass: "video__wrapper",
  slideClass: "video__slide",
  slidesPerView: "auto",
  spaceBetween: 30,
});

var map = document.getElementById("map");

const bigImg = document.querySelector(".model__big img");
const smallImg = document.querySelectorAll(".model__img img");

smallImg.forEach((item) => {
  item.addEventListener("click", () => {
    smallImgSrc = item.src;
    bigImg.src = smallImgSrc;
  });
});

function initMap() {
  const center = { lat: 49.41832849437991, lng: 26.980854698857758 };
  map = new google.maps.Map(map, {
    center: center,
    mapId: "1be87eee1d42f34d",
    zoom: 5,
  });

  const tourStops = [
    [{ lat: 51.41832849437991, lng: 26.980854698857758 }, "Boynton Pass"],
    [{ lat: 60.41832849437991, lng: 26.980854698857758 }, "Airport Mesa"],
    [
      { lat: 54.41832849437991, lng: 26.980854698857758 },
      "Chapel of the Holy Cross",
    ],
    [{ lat: 65.41832849437991, lng: 26.980854698857758 }, "Red Rock Crossing"],
    [{ lat: 33.41832849437991, lng: 26.980854698857758 }, "Bell Rock"],
    [{ lat: 49.41832849437991, lng: 26.980854698857758 }, "NOVIAS"],
  ];

  const infoWindow = new google.maps.InfoWindow();

  var image = {
    url: "https://www.svgrepo.com/show/171081/store.svg",
    size: new google.maps.Size(71, 71),
    origin: new google.maps.Point(0, 0),
    anchor: new google.maps.Point(17, 34),
    scaledSize: new google.maps.Size(25, 25),
  };

  tourStops.forEach(([position, title], i) => {
    const marker = new google.maps.Marker({
      position,
      icon: image,
      map,
      title: `${title}`,
      optimized: false,
    });

    // Add a click listener for each marker, and set up the info window.
    marker.addListener("click", () => {
      infoWindow.close();
      infoWindow.setContent(marker.getTitle());
      infoWindow.open(marker.getMap(), marker);
    });
  });
}

const video = document.querySelectorAll(".video__slide");
const playVideo = document.querySelectorAll(".video__slide button");

video.forEach((item) => {
  item.lastElementChild.addEventListener("click", () => {
    item.lastElementChild.setAttribute("style", "display: none");
    item.firstElementChild.setAttribute("controls", "true");
    item.firstElementChild.play();
  });
});

console.log(window.innerWidth);

blogSlider();
if (window.innerWidth <= 768) {
  const modelNav = document.querySelector(".model__nav");
  const modelLeft = document.querySelector(".model__left");
  modelLeft.insertBefore(modelNav, modelLeft.firstChild);
}
