// pagination in page wishlist

document.addEventListener("DOMContentLoaded", function () {
    const itemsPerPage = 6;
    const cards = document.querySelectorAll(".card-event");
    const pagination = document.getElementById("pagination");
    const totalPages = Math.ceil(cards.length / itemsPerPage);

    function showPage(page) {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;

        cards.forEach((card, index) => {
            if (index >= start && index < end) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        });

        Array.from(pagination.children).forEach((button) => {
            button.classList.remove("active");
        });
        pagination.children[page - 1].classList.add("active");
    }

    function createPagination() {
        for (let i = 1; i <= totalPages; i++) {
            const button = document.createElement("button");
            button.innerText = i;
            button.addEventListener("click", () => showPage(i));
            if (i === 1) {
                button.classList.add("active");
            }
            pagination.appendChild(button);
        }
    }

    createPagination();
    showPage(1);
});

// sideMenu
const allSideMenu = document.querySelectorAll("#sidebar .side-menu.top li a");

allSideMenu.forEach((item) => {
    const li = item.parentElement;

    item.addEventListener("click", function () {
        allSideMenu.forEach((i) => {
            i.parentElement.classList.remove("active");
        });
        li.classList.add("active");
    });
});

// TOGGLE SIDEBAR
const menuBar = document.querySelector("#content nav .bx.bx-menu");
const sidebar = document.getElementById("sidebar");

menuBar.addEventListener("click", function () {
    sidebar.classList.toggle("hide");
});

const searchButton = document.querySelector(
    "#content nav form .form-input button"
);
const searchButtonIcon = document.querySelector(
    "#content nav form .form-input button .bx"
);
const searchForm = document.querySelector("#content nav form");

searchButton.addEventListener("click", function (e) {
    if (window.innerWidth < 576) {
        e.preventDefault();
        searchForm.classList.toggle("show");
        if (searchForm.classList.contains("show")) {
            searchButtonIcon.classList.replace("bx-search", "bx-x");
        } else {
            searchButtonIcon.classList.replace("bx-x", "bx-search");
        }
    }
});

if (window.innerWidth < 768) {
    sidebar.classList.add("hide");
} else if (window.innerWidth > 576) {
    searchButtonIcon.classList.replace("bx-x", "bx-search");
    searchForm.classList.remove("show");
}

window.addEventListener("resize", function () {
    if (this.innerWidth > 576) {
        searchButtonIcon.classList.replace("bx-x", "bx-search");
        searchForm.classList.remove("show");
    }
});

const switchMode = document.getElementById("switch-mode");

switchMode.addEventListener("change", function () {
    if (this.checked) {
        document.body.classList.add("dark");
    } else {
        document.body.classList.remove("dark");
    }
});

// poster image
function loadFile(event) {
    var image = document.getElementById("uploadedImage");
    image.src = URL.createObjectURL(event.target.files[0]);
    image.style.display = "block";
}

// banner image
function loadCustomFile(event) {
    var image = document.getElementById("customUploadedImage");
    image.src = URL.createObjectURL(event.target.files[0]);
    image.style.display = "block";
}



