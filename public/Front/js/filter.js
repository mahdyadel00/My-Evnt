/**
 * Nebule Event Filter System
 * Handles: search, category, city, date, price filters
 * With pagination, active filter tags, and smooth animations
 */

(function () {
  "use strict";

  // ─── CONFIG ──────────────────────────────────────────────────
  const CARDS_PER_PAGE = 8;

  // ─── CACHE DOM ───────────────────────────────────────────────
  const cardsContainer = document.getElementById("cardsContainer");
  const searchInput = document.getElementById("nebule-event-offcanvas-search");
  // Top inline search in header section of filtration page
  const headerSearchInput = document.querySelector(".filteration-event-search-input");
  const dateInput = document.getElementById("nebule-event-offcanvas-date");
  const filterForm = document.getElementById("nebule-event-filter-form");
  const applyBtn = filterForm?.querySelector(".nebule-event-offcanvas-apply-btn");
  const clearBtn = filterForm?.querySelector(".nebule-event-offcanvas-clear-btn");

  // ─── STATE ───────────────────────────────────────────────────
  let allCards = [];          // master list (never mutated)
  let filteredCards = [];     // current filtered set
  let currentPage = 1;
  let totalPages = 1;

  // pending = what the user is building inside the offcanvas before hitting Apply
  let pendingFilters = { search: "", category: [], city: [], date: "", price: [] };
  // active  = what is actually applied to the card list
  let activeFilters  = { search: "", category: [], city: [], date: "", price: [] };

  // Refresh cards from DOM (e.g. after AJAX category search)
  function refreshCardsFromContainer() {
      if (!cardsContainer) return;
      allCards = Array.from(cardsContainer.querySelectorAll(".filteration-event-card"));
      allCards.forEach((card) => {
          if (!card.dataset.eventCategory) card.dataset.eventCategory = "";
          if (!card.dataset.eventCity)     card.dataset.eventCity     = "";
          if (!card.dataset.eventDate)     card.dataset.eventDate     = "";
          if (!card.dataset.eventPrice)    card.dataset.eventPrice    = "";
      });
      activeFilters  = { search: "", category: [], city: [], date: "", price: [] };
      pendingFilters = deepClone(activeFilters);
      currentPage = 1;
      renderCards();
      renderActiveTags();
  }

  // ─── INIT ────────────────────────────────────────────────────
  document.addEventListener("DOMContentLoaded", () => {
      // Grab every card once and store references
      allCards = Array.from(cardsContainer.querySelectorAll(".filteration-event-card"));

      // Ensure every card has the needed data-attributes (fallback for cards that are missing them)
      allCards.forEach((card) => {
          if (!card.dataset.eventCategory) card.dataset.eventCategory = "";
          if (!card.dataset.eventCity)     card.dataset.eventCity     = "";
          if (!card.dataset.eventDate)     card.dataset.eventDate     = "";
          if (!card.dataset.eventPrice)    card.dataset.eventPrice    = "";
      });

      bindEvents();
      initFromUrl();       // read URL params → populate activeFilters
      renderCards();       // initial render
      renderActiveTags();  // show any URL-based active filters as tags

      // After AJAX category search updates the container, refresh local card list
      window.addEventListener("eventsContainerUpdated", refreshCardsFromContainer);
  });

  // ─── INIT FROM URL PARAMS ─────────────────────────────────────
  function initFromUrl() {
      const params = new URLSearchParams(window.location.search);

      const category = params.get('category');
      if (category && category !== 'all') {
          activeFilters.category = [category];
          pendingFilters.category = [category];
      }

      const city = params.get('city');
      if (city) {
          activeFilters.city = [city];
          pendingFilters.city = [city];
      }

      const date = params.get('date');
      if (date) {
          activeFilters.date = date;
          pendingFilters.date = date;
          if (dateInput) dateInput.value = date;
      }

      const search = params.get('search') || params.get('q');
      if (search) {
          activeFilters.search = search.toLowerCase();
          pendingFilters.search = search.toLowerCase();
          if (searchInput) searchInput.value = search;
          if (headerSearchInput) headerSearchInput.value = search;
      }
  }

  // ─── EVENTS ──────────────────────────────────────────────────
  function bindEvents() {
      // Apply button → commit pending filters and render
      applyBtn?.addEventListener("click", (e) => {
          e.preventDefault();
          commitFilters();
          closeOffcanvas();
      });

      // Clear button → reset everything
      clearBtn?.addEventListener("click", (e) => {
          e.preventDefault();
          resetAllFilters();
          closeOffcanvas();
      });

      // Live search inside offcanvas (updates pending state only, applied on Apply)
      searchInput?.addEventListener("input", () => {
          pendingFilters.search = searchInput.value.trim().toLowerCase();
      });

      // Live search in top header search bar (immediately filters cards, no reload)
      headerSearchInput?.addEventListener("input", () => {
          const value = headerSearchInput.value.trim().toLowerCase();
          // Keep pending & active in sync for this field
          pendingFilters.search = value;
          activeFilters.search = value;
          currentPage = 1;
          renderCards();
          renderActiveTags();
      });

      // Delegate checkbox changes – no need to bind each one
      filterForm?.addEventListener("change", (e) => {
          const checkbox = e.target;
          if (checkbox.type !== "checkbox") return;

          const filterType = checkbox.dataset.filter; // "category" | "city" | "price"
          if (!filterType) return;

          if (checkbox.checked) {
              if (!pendingFilters[filterType].includes(checkbox.value)) {
                  pendingFilters[filterType].push(checkbox.value);
              }
          } else {
              pendingFilters[filterType] = pendingFilters[filterType].filter(
                  (v) => v !== checkbox.value
              );
          }
      });

      // Date input change
      dateInput?.addEventListener("change", () => {
          pendingFilters.date = dateInput.value || "";
      });

      // When the offcanvas is about to show, sync checkboxes to activeFilters
      document.addEventListener("bs:offcanvasShow", () => {
          syncOffcanvasToActive();
      });
  }

  // ─── SYNC offcanvas UI ↔ active state ────────────────────────
  /**
   * Before showing the offcanvas, set every checkbox / input
   * to match what is currently applied (activeFilters).
   */
  function syncOffcanvasToActive() {
      // Reset pending to a copy of active
      pendingFilters = deepClone(activeFilters);

      // Search input
      if (searchInput) searchInput.value = activeFilters.search;

      // Checkboxes
      filterForm?.querySelectorAll("input[type='checkbox'][data-filter]").forEach((cb) => {
          cb.checked = activeFilters[cb.dataset.filter]?.includes(cb.value) || false;
      });

      // Date
      if (dateInput) dateInput.value = activeFilters.date;
  }

  // ─── COMMIT & RESET ──────────────────────────────────────────
  function commitFilters() {
      activeFilters = deepClone(pendingFilters);
      currentPage = 1;
      renderCards();
      renderActiveTags();
  }

  function resetAllFilters() {
      // If there are URL params (server-side filters), redirect to clean URL
      if (window.location.search && window.location.search !== '') {
          window.location.href = window.location.pathname;
          return;
      }

      activeFilters  = { search: "", category: [], city: [], date: "", price: [] };
      pendingFilters = deepClone(activeFilters);

      // Clear UI
      if (searchInput) searchInput.value = "";
      if (dateInput)   dateInput.value   = "";
      if (headerSearchInput) headerSearchInput.value = "";
      filterForm?.querySelectorAll("input[type='checkbox']").forEach((cb) => (cb.checked = false));

      currentPage = 1;
      renderCards();
      renderActiveTags();

      // Scroll to top
      window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  // Expose globally so inline onclick handlers work
  window.resetAllFilters = resetAllFilters;

  // ─── FILTER LOGIC ────────────────────────────────────────────
  function getFilteredCards() {
      return allCards.filter((card) => {
          // 1) Search by title
          if (activeFilters.search) {
              const title = card.querySelector(".filteration-event-card-title")?.textContent || "";
              if (!title.toLowerCase().includes(activeFilters.search)) return false;
          }

          // 2) Category (OR logic – card matches if its category is in the selected list)
          if (activeFilters.category.length > 0) {
              if (!activeFilters.category.includes(card.dataset.eventCategory)) return false;
          }

          // 3) City
          if (activeFilters.city.length > 0) {
              if (!activeFilters.city.includes(card.dataset.eventCity)) return false;
          }

          // 4) Date – show cards on or after the selected date
          if (activeFilters.date) {
              const cardDate = card.dataset.eventDate;
              if (!cardDate || cardDate < activeFilters.date) return false;
          }

          // 5) Price
          if (activeFilters.price.length > 0) {
              if (!activeFilters.price.includes(card.dataset.eventPrice)) return false;
          }

          return true;
      });
  }

  // ─── RENDER CARDS (paginated) ────────────────────────────────
  function renderCards() {
      filteredCards = getFilteredCards();
      totalPages    = Math.max(1, Math.ceil(filteredCards.length / CARDS_PER_PAGE));
      if (currentPage > totalPages) currentPage = totalPages;

      const start = (currentPage - 1) * CARDS_PER_PAGE;
      const pageCards = filteredCards.slice(start, start + CARDS_PER_PAGE);

      // Hide all, then show only the current page's cards
      allCards.forEach((card) => {
          card.style.display = "none";
          card.classList.remove("card-visible");
      });

      pageCards.forEach((card, i) => {
          card.style.display = "";
          // staggered fade-in
          requestAnimationFrame(() => {
              setTimeout(() => card.classList.add("card-visible"), i * 60);
          });
      });

      // Show / hide empty state
      showEmptyState(pageCards.length === 0);

      renderPagination();
  }

  // ─── EMPTY STATE ─────────────────────────────────────────────
  function showEmptyState(show) {
      let empty = cardsContainer.parentElement.querySelector(".filteration-event-empty");
      if (show && !empty) {
          empty = document.createElement("div");
          empty.className = "filteration-event-empty";
          empty.innerHTML = `
              <div class="filteration-event-empty-icon">🔍</div>
              <p class="filteration-event-empty-text">No events match your filters.</p>
              <button class="filteration-event-empty-reset" onclick="resetAllFilters()">Clear Filters</button>
          `;
          cardsContainer.parentElement.insertBefore(empty, cardsContainer.nextSibling);
      }
      if (empty) empty.style.display = show ? "flex" : "none";
  }

  // ─── PAGINATION ──────────────────────────────────────────────
  function renderPagination() {
      const wrapper = document.querySelector(".filteration-event-pagination");
      if (!wrapper) return;

      // Keep prev / next buttons, rebuild number buttons in between
      const prevBtn = wrapper.querySelector("#prevPageBtn");
      const nextBtn = wrapper.querySelector("#nextPageBtn");

      // Remove old page buttons & dots
      wrapper.querySelectorAll(".filteration-event-pagination-btn:not(#prevPageBtn):not(#nextPageBtn), .filteration-event-pagination-dots").forEach(
          (el) => el.remove()
      );

      const pages = getPageNumbers(currentPage, totalPages);

      pages.forEach((p) => {
          if (p === "...") {
              const dots = document.createElement("span");
              dots.className = "filteration-event-pagination-dots";
              dots.textContent = "...";
              wrapper.insertBefore(dots, nextBtn);
          } else {
              const btn = document.createElement("button");
              btn.className = "filteration-event-pagination-btn" + (p === currentPage ? " active" : "");
              btn.dataset.page = p;
              btn.textContent = p;
              btn.onclick = () => changePage(p);
              wrapper.insertBefore(btn, nextBtn);
          }
      });

      // Update prev / next state
      prevBtn.classList.toggle("disabled", currentPage <= 1);
      nextBtn.classList.toggle("disabled", currentPage >= totalPages);

      prevBtn.onclick = () => changePage("prev");
      nextBtn.onclick = () => changePage("next");
  }

  /**
   * Build a smart page-number array like: [1, 2, 3, "...", 8]
   */
  function getPageNumbers(current, total) {
      if (total <= 5) {
          return Array.from({ length: total }, (_, i) => i + 1);
      }

      const pages = [];
      pages.push(1);

      if (current > 3) pages.push("...");

      for (let i = Math.max(2, current - 1); i <= Math.min(total - 1, current + 1); i++) {
          pages.push(i);
      }

      if (current < total - 2) pages.push("...");

      pages.push(total);
      return pages;
  }

  // Exposed globally so inline onclick handlers work
  window.changePage = function (direction) {
      if (direction === "prev") {
          if (currentPage > 1) currentPage--;
      } else if (direction === "next") {
          if (currentPage < totalPages) currentPage++;
      } else {
          currentPage = Number(direction);
      }
      renderCards();
      // Smooth scroll to top of cards
      cardsContainer?.scrollIntoView({ behavior: "smooth", block: "start" });
  };

  // ─── ACTIVE FILTER TAGS (optional enhancement) ──────────────
  /**
   * Renders small removable tag pills above the card grid
   * showing which filters are currently active.
   */
  function renderActiveTags() {
      let tagsContainer = cardsContainer.parentElement.querySelector(".filteration-event-tags");

      if (!tagsContainer) {
          tagsContainer = document.createElement("div");
          tagsContainer.className = "filteration-event-tags";
          cardsContainer.parentElement.insertBefore(tagsContainer, cardsContainer);
      }

      tagsContainer.innerHTML = "";

      const addTag = (label, removeCallback) => {
          const tag = document.createElement("span");
          tag.className = "filteration-event-tag";
          tag.innerHTML = `${label} <button class="filteration-event-tag-remove" aria-label="Remove filter">&times;</button>`;
          tag.querySelector("button").addEventListener("click", removeCallback);
          tagsContainer.appendChild(tag);
      };

      if (activeFilters.search) {
          addTag(`Search: "${activeFilters.search}"`, () => {
              activeFilters.search = "";
              commitFromActive();
          });
      }

      activeFilters.category.forEach((val) => {
          addTag(`Category: ${capitalize(val)}`, () => {
              activeFilters.category = activeFilters.category.filter((v) => v !== val);
              commitFromActive();
          });
      });

      activeFilters.city.forEach((val) => {
          addTag(`City: ${capitalize(val)}`, () => {
              activeFilters.city = activeFilters.city.filter((v) => v !== val);
              commitFromActive();
          });
      });

      if (activeFilters.date) {
          addTag(`Date: ${activeFilters.date}`, () => {
              activeFilters.date = "";
              commitFromActive();
          });
      }

      activeFilters.price.forEach((val) => {
          addTag(`Price: ${capitalize(val)}`, () => {
              activeFilters.price = activeFilters.price.filter((v) => v !== val);
              commitFromActive();
          });
      });

      // Show / hide the whole bar
      tagsContainer.style.display = tagsContainer.children.length > 0 ? "flex" : "none";
  }

  /** Re-apply activeFilters directly (used when removing a tag) */
  function commitFromActive() {
      pendingFilters = deepClone(activeFilters);
      currentPage = 1;
      renderCards();
      renderActiveTags();
  }

  // ─── SLIDER (kept for compatibility) ─────────────────────────
  window.slideCards = function (direction) {
      const container = document.querySelector(".filteration-event-cards-grid");
      if (!container) return;
      const scrollAmount = container.offsetWidth * 0.4;
      container.scrollBy({
          left: direction === "next" ? scrollAmount : -scrollAmount,
          behavior: "smooth",
      });
  };

  // ─── UTILS ───────────────────────────────────────────────────
  function deepClone(obj) {
      return JSON.parse(JSON.stringify(obj));
  }

  function capitalize(str) {
      return str.charAt(0).toUpperCase() + str.slice(1).replace(/-/g, " ");
  }

  function closeOffcanvas() {
      const offcanvas = document.getElementById("offcanvasScrolling");
      if (offcanvas) {
          const instance = bootstrap.Offcanvas.getInstance(offcanvas);
          instance?.hide();
      }
  }

})();


// Initialize slider buttons on load


document.addEventListener("DOMContentLoaded", function () {
  // Category AJAX: prevent page reload, filter by category
  const categoryAjaxItems = document.querySelectorAll(".filteration-event-category-item-ajax");
  categoryAjaxItems.forEach((item) => {
      item.addEventListener("click", function (e) {
          e.preventDefault();
          var categoryId = this.getAttribute("data-category-id");
          if (!categoryId) return;

          // Update selected state (CSS uses .selected)
          categoryAjaxItems.forEach(function (el) { el.classList.remove("selected"); });
          this.classList.add("selected");

          if (typeof window.performAjaxSearch !== "function") return;
          if (categoryId === "all") {
              window.performAjaxSearch(null, null, null, {});
          } else {
              window.performAjaxSearch(null, null, null, { category: [categoryId] });
          }
      });
  });

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


