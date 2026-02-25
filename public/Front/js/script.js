/// profile dropdown
document.addEventListener("DOMContentLoaded", function () {
    const profile = document.querySelector(".profile");
    const imgProfile = profile.querySelector("img");
    const dropdownProfile = profile.querySelector(".profile-link");

    imgProfile.addEventListener("click", function () {
        dropdownProfile.classList.toggle("show");
    });
});

const menu = document.querySelector(".menu");
const menuMain = menu.querySelector(".menu-main");
const goBack = menu.querySelector(".go-back");
const menuTrigger = document.querySelector(".mobile-menu-trigger");
const closeMenu = menu.querySelector(".mobile-menu-close");
let subMenu;
menuMain.addEventListener("click", (e) => {
    if (!menu.classList.contains("active")) {
        return;
    }
    if (e.target.closest(".menu-item-has-children")) {
        const hasChildren = e.target.closest(".menu-item-has-children");
        showSubMenu(hasChildren);
    }
});
goBack.addEventListener("click", () => {
    hideSubMenu();
});
menuTrigger.addEventListener("click", () => {
    toggleMenu();
});
closeMenu.addEventListener("click", () => {
    toggleMenu();
});
document.querySelector(".menu-overlay").addEventListener("click", () => {
    toggleMenu();
});
function toggleMenu() {
    menu.classList.toggle("active");
    document.querySelector(".menu-overlay").classList.toggle("active");
}
function showSubMenu(hasChildren) {
    subMenu = hasChildren.querySelector(".sub-menu");
    subMenu.classList.add("active");
    subMenu.style.animation = "slideLeft 0.5s ease forwards";
    const menuTitle =
        hasChildren.querySelector("i").parentNode.childNodes[0].textContent;
    menu.querySelector(".current-menu-title").innerHTML = menuTitle;
    menu.querySelector(".mobile-menu-head").classList.add("active");
}

function hideSubMenu() {
    subMenu.style.animation = "slideRight 0.5s ease forwards";
    setTimeout(() => {
        subMenu.classList.remove("active");
    }, 300);
    menu.querySelector(".current-menu-title").innerHTML = "";
    menu.querySelector(".mobile-menu-head").classList.remove("active");
}

window.onresize = function () {
    if (this.innerWidth > 991) {
        if (menu.classList.contains("active")) {
            toggleMenu();
        }
    }
};

// // slider script

// const myslide = document.querySelectorAll(".slider_content");
// dot = document.querySelectorAll(".dot");
// let counter = 1;
// slidefun(counter);
//
// let timer = setInterval(autoSlide, 8000);
// function autoSlide() {
//     counter += 1;
//     slidefun(counter);
// }
// function plusSlides(n) {
//     counter += n;
//     slidefun(counter);
//     resetTimer();
// }
// function currentSlide(n) {
//     counter = n;
//     slidefun(counter);
//     resetTimer();
// }
// function resetTimer() {
//     clearInterval(timer);
//     timer = setInterval(autoSlide, 8000);
// }
//
// function slidefun(n) {
//     let i;
//     for (i = 0; i < myslide.length; i++) {
//         myslide[i].style.display = "none";
//     }
//     for (i = 0; i < dot.length; i++) {
//         dot[i].className = dot[i].className.replace(" active", "");
//     }
//     if (n > myslide.length) {
//         counter = 1;
//     }
//     if (n < 1) {
//         counter = myslide.length;
//     }
//     myslide[counter - 1].style.display = "block";
//     dot[counter - 1].className += " active";
// }
//
// // slider cards event
// var swiper = new Swiper(".mySwiper", {
//     slidesPerView: 1,
//     spaceBetween: 10,
//     loop: true,
//     navigation: {
//         nextEl: ".swiper-button-next",
//         prevEl: ".swiper-button-prev",
//     },
//     breakpoints: {
//         640: {
//             slidesPerView: 2,
//             spaceBetween: 20,
//         },
//         768: {
//             slidesPerView: 2,
//             spaceBetween: 30,
//         },
//         1024: {
//             slidesPerView: 4,
//             spaceBetween: 40,
//         },
//     },
// });

// dropdown in page all event
function toggleDropdown(button) {
    var dropdownContent = button.nextElementSibling;
    if (dropdownContent.style.display === "block") {
        dropdownContent.style.display = "none";
    } else {
        dropdownContent.style.display = "block";
    }
}

// Close the dropdown if the user clicks outside of it
window.onclick = function (event) {
    if (!event.target.matches(".dropbtn")) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.style.display === "block") {
                openDropdown.style.display = "none";
            }
        }
    }
};

// to search in event by the name
function filterProducts() {
    var input, filter, allEventSection, products, productName, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    allEventSection = document.querySelector(".col-md-9.all-event");
    products = allEventSection.getElementsByClassName("product");

    for (i = 0; i < products.length; i++) {
        productName = products[i].querySelector(".product-name");
        if (productName) {
            txtValue = productName.textContent || productName.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                products[i].style.display = "";
            } else {
                products[i].style.display = "none";
            }
        }
    }
}

// section blogs slider
// const wrapper = document.querySelector(".blog-wrapper");
// const indicators = [...document.querySelectorAll(".indicators button")];

// let currentTestimonial = 0; // Default 0

// indicators.forEach((item, i) => {
//     item.addEventListener("click", () => {
//         indicators[currentTestimonial].classList.remove("active");
//         wrapper.style.marginLeft = `-${100 * i}%`;
//         item.classList.add("active");
//         currentTestimonial = i;
//     });
// });

// slider blog
var swiper = new Swiper(".swiper-container", {
    slidesPerView: 1,
    spaceBetween: 30,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
});
