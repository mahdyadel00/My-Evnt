//
// Static JavaScript for UI interactions only
// Ajax filtering is handled in index.blade.php

document.addEventListener("DOMContentLoaded", function () {
  // Add hover effects for category items
  const categoryItems = document.querySelectorAll(
      ".filteration-event-category-item"
  );
  categoryItems.forEach((item) => {
      item.addEventListener("mouseenter", function () {
          this.style.transform = "translateY(-3px)";
      });

      item.addEventListener("mouseleave", function () {
          this.style.transform = "translateY(0)";
      });
  });

  // Add click effects for buttons
  const buttons = document.querySelectorAll(
      ".filteration-event-action-button"
  );
  buttons.forEach((button) => {
      button.addEventListener("click", function () {
          this.style.transform = "translateY(-2px)";
          setTimeout(() => {
              this.style.transform = "translateY(0)";
          }, 150);
      });
  });

  // Add search input focus effects
  const searchInput = document.querySelector(
      ".filteration-event-search-input"
  );
  if (searchInput) {
      searchInput.addEventListener("focus", function () {
          this.parentElement.style.transform = "scale(1.02)";
      });

      searchInput.addEventListener("blur", function () {
          this.parentElement.style.transform = "scale(1)";
      });
  }
});

// Enhanced Category Slider Functionality with responsive handling
let currentSlide = 0;
let totalSlides = 3;
let viewMode = "slider"; // 'slider' or 'grid'
let isMobile = window.innerWidth <= 768;
let isTablet = window.innerWidth > 768 && window.innerWidth <= 1024;

// Update responsive variables on resize
window.addEventListener("resize", function () {
  const newIsMobile = window.innerWidth <= 768;
  const newIsTablet = window.innerWidth > 768 && window.innerWidth <= 1024;

  if (newIsMobile !== isMobile || newIsTablet !== isTablet) {
      isMobile = newIsMobile;
      isTablet = newIsTablet;

      // Recalculate slide amounts based on new screen size
      setTimeout(() => {
          updateSliderButtons();
          updateCardsButtons();
      }, 100);
  }
});

function slideCategories(direction) {
  const slider = document.getElementById("categorySlider");
  // Responsive scroll amounts
  let scrollAmount = 300;
  if (isMobile) {
      scrollAmount = 200;
  } else if (isTablet) {
      scrollAmount = 250;
  }

  if (direction === "prev") {
      slider.scrollBy({
          left: -scrollAmount,
          behavior: "smooth",
      });
      if (currentSlide > 0) currentSlide--;
  } else if (direction === "next") {
      slider.scrollBy({
          left: scrollAmount,
          behavior: "smooth",
      });
      if (currentSlide < totalSlides - 1) currentSlide++;
  }

  // Update button states
  setTimeout(() => {
      updateSliderButtons();
  }, 300);
}

function goToSlide(slideIndex) {
  const slider = document.getElementById("categorySlider");
  const scrollAmount = slideIndex * 300;

  slider.scrollTo({
      left: scrollAmount,
      behavior: "smooth",
  });

  currentSlide = slideIndex;
  updateSliderButtons();
}

function toggleView(mode) {
  viewMode = mode;
  const sliderBtn = document.getElementById("sliderViewBtn");
  const gridBtn = document.getElementById("gridViewBtn");
  const slider = document.getElementById("categorySlider");
  const controls = document.querySelector(
      ".filteration-event-slider-controls"
  );

  if (mode === "slider") {
      sliderBtn.classList.add("active");
      gridBtn.classList.remove("active");
      slider.classList.remove("grid-view");
      controls.style.display = "flex";
  } else {
      gridBtn.classList.add("active");
      sliderBtn.classList.remove("active");
      slider.classList.add("grid-view");
      controls.style.display = "none";
  }
}

function updateSliderButtons() {
  const slider = document.getElementById("categorySlider");
  const prevBtn = document.getElementById("prevBtn");
  const nextBtn = document.getElementById("nextBtn");

  if (!slider || !prevBtn || !nextBtn) return;

  // Check if we're at the beginning
  if (slider.scrollLeft <= 10) {
      prevBtn.disabled = true;
      prevBtn.classList.add("disabled");
  } else {
      prevBtn.disabled = false;
      prevBtn.classList.remove("disabled");
  }

  // Check if we're at the end
  if (slider.scrollLeft >= slider.scrollWidth - slider.clientWidth - 10) {
      nextBtn.disabled = true;
      nextBtn.classList.add("disabled");
  } else {
      nextBtn.disabled = false;
      nextBtn.classList.remove("disabled");
  }
}

// Cards view management - Default grid view
let cardsViewMode = "grid"; // Default to grid view
let currentCardsSlide = 0;
const totalCardsSlides = 4;

function toggleCardsView(mode) {
  cardsViewMode = mode;
  const gridBtn = document.getElementById("cardsGridViewBtn");
  const sliderBtn = document.getElementById("cardsSliderViewBtn");
  const cardsContainer = document.getElementById("cardsContainer");
  const cardsControls = document.getElementById("cardsControls");
  const pagination = document.querySelector(".filteration-event-pagination");

  if (mode === "grid") {
      gridBtn.classList.add("active");
      sliderBtn.classList.remove("active");
      cardsContainer.classList.remove("slider-view");
      cardsControls.style.display = "none";
      pagination.style.display = "flex";
  } else {
      sliderBtn.classList.add("active");
      gridBtn.classList.remove("active");
      cardsContainer.classList.add("slider-view");
      cardsControls.style.display = "flex";
      pagination.style.display = "none";
  }

  // Update immediately without delay
  updateCardsButtons();
}

function slideCards(direction) {
  const cardsContainer = document.getElementById("cardsContainer");
  // Responsive scroll amounts for cards
  let scrollAmount = 350;
  if (isMobile) {
      scrollAmount = 250;
  } else if (isTablet) {
      scrollAmount = 300;
  }

  if (direction === "prev") {
      cardsContainer.scrollBy({
          left: -scrollAmount,
          behavior: "smooth",
      });
      if (currentCardsSlide > 0) currentCardsSlide--;
  } else if (direction === "next") {
      cardsContainer.scrollBy({
          left: scrollAmount,
          behavior: "smooth",
      });
      if (currentCardsSlide < totalCardsSlides - 1) currentCardsSlide++;
  }

  // Update immediately
  updateCardsButtons();
}

function goToCardsSlide(slideIndex) {
  const cardsContainer = document.getElementById("cardsContainer");
  const scrollAmount = slideIndex * 350;

  cardsContainer.scrollTo({
      left: scrollAmount,
      behavior: "smooth",
  });

  currentCardsSlide = slideIndex;
  updateCardsButtons();
}

function updateCardsButtons() {
  const cardsContainer = document.getElementById("cardsContainer");
  const prevBtn = document.getElementById("prevCardsBtn");
  const nextBtn = document.getElementById("nextCardsBtn");

  if (!cardsContainer || cardsViewMode !== "slider" || !prevBtn || !nextBtn) return;

  // Check if we're at the beginning
  if (cardsContainer.scrollLeft <= 10) {
      prevBtn.disabled = true;
      prevBtn.classList.add("disabled");
  } else {
      prevBtn.disabled = false;
      prevBtn.classList.remove("disabled");
  }

  // Check if we're at the end
  if (
      cardsContainer.scrollLeft >=
      cardsContainer.scrollWidth - cardsContainer.clientWidth - 10
  ) {
      nextBtn.disabled = true;
      nextBtn.classList.add("disabled");
  } else {
      nextBtn.disabled = false;
      nextBtn.classList.remove("disabled");
  }
}

// Initialize slider buttons on load
document.addEventListener("DOMContentLoaded", function () {
  updateSliderButtons();
  updateCardsButtons();

  // Update buttons on scroll
  const slider = document.getElementById("categorySlider");
  const cardsContainer = document.getElementById("cardsContainer");

  if (slider) {
      slider.addEventListener("scroll", updateSliderButtons);
  }
  
  if (cardsContainer) {
      cardsContainer.addEventListener("scroll", updateCardsButtons);
  }

  // Enhanced touch support for mobile devices
  if ("ontouchstart" in window) {
      // Add touch feedback for interactive elements
      const touchElements = document.querySelectorAll(
          ".filteration-event-action-button, .filteration-event-details-btn, .filteration-event-pagination-btn, .filteration-event-slider-btn, .filteration-event-toggle-btn"
      );

      touchElements.forEach((element) => {
          element.addEventListener("touchstart", function () {
              this.style.opacity = "0.7";
              this.style.transform = "scale(0.95)";
          });

          element.addEventListener("touchend", function () {
              this.style.opacity = "1";
              this.style.transform = "";
          });

          element.addEventListener("touchcancel", function () {
              this.style.opacity = "1";
              this.style.transform = "";
          });
      });
  }

  // Handle orientation change
  window.addEventListener("orientationchange", function () {
      setTimeout(() => {
          updateSliderButtons();
          updateCardsButtons();
      }, 100);
  });

  // Improve accessibility
  const categoryItems = document.querySelectorAll(
      ".filteration-event-category-item"
  );
  categoryItems.forEach((item, index) => {
      item.setAttribute("role", "button");
      item.setAttribute("tabindex", "0");
      
      const label = item.querySelector(".filteration-event-category-label");
      if (label) {
          item.setAttribute(
              "aria-label",
              `Select ${label.textContent} category`
          );
      }

      // Add keyboard support
      item.addEventListener("keydown", function (e) {
          if (e.key === "Enter" || e.key === " ") {
              e.preventDefault();
              this.click();
          }
      });
  });
});

// Custom Select Dropdowns (UI only, filtering handled by jQuery in blade file)
class FilterationEventFilters {
  constructor() {
      this.init();
  }

  init() {
      this.setupCustomSelects();
      this.setupClickOutside();
  }

  setupCustomSelects() {
      const selects = document.querySelectorAll(
          ".filteration-event-custom-select"
      );

      selects.forEach((select) => {
          const button = select.querySelector(
              ".filteration-event-select-button"
          );
          const dropdown = select.querySelector(
              ".filteration-event-select-dropdown"
          );

          // Toggle dropdown
          if (button) {
              button.addEventListener("click", (e) => {
                  e.stopPropagation();
                  this.closeAllDropdowns();
                  this.toggleDropdown(select);
              });
          }
      });
  }

  setupClickOutside() {
      document.addEventListener("click", () => {
          this.closeAllDropdowns();
      });
  }

  toggleDropdown(select) {
      const button = select.querySelector(".filteration-event-select-button");
      const dropdown = select.querySelector(
          ".filteration-event-select-dropdown"
      );

      const isActive = button.classList.contains(
          "filteration-event-select-active"
      );

      if (isActive) {
          this.closeDropdown(select);
      } else {
          this.openDropdown(select);
      }
  }

  openDropdown(select) {
      const button = select.querySelector(".filteration-event-select-button");
      const dropdown = select.querySelector(
          ".filteration-event-select-dropdown"
      );

      button.classList.add("filteration-event-select-active");
      dropdown.classList.add("filteration-event-select-dropdown-active");

      // Add loading animation
      button.classList.add("filteration-event-loading");
      setTimeout(
          () => button.classList.remove("filteration-event-loading"),
          400
      );
  }

  closeDropdown(select) {
      const button = select.querySelector(".filteration-event-select-button");
      const dropdown = select.querySelector(
          ".filteration-event-select-dropdown"
      );

      button.classList.remove("filteration-event-select-active");
      dropdown.classList.remove("filteration-event-select-dropdown-active");
  }

  closeAllDropdowns() {
      const selects = document.querySelectorAll(
          ".filteration-event-custom-select"
      );
      selects.forEach((select) => this.closeDropdown(select));
  }
}

// Initialize the filters UI
document.addEventListener("DOMContentLoaded", () => {
  window.filterationEventFilters = new FilterationEventFilters();
});