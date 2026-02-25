// ScrollReveal Animations
ScrollReveal({
  reset: true,
  distance: "60px",
  duration: 2500,
  delay: 100,
});

// Header animations
ScrollReveal().reveal(".header__content h2", { delay: 200, origin: "top" });
ScrollReveal().reveal(".header__content h1", { delay: 300, origin: "left" });
ScrollReveal().reveal(".header__content p", { delay: 400, origin: "right" });
ScrollReveal().reveal(".header__time", { delay: 450, origin: "bottom" });
ScrollReveal().reveal(".header__btn", { delay: 500, origin: "bottom" });
ScrollReveal().reveal(".socials", { delay: 600, origin: "bottom" });
ScrollReveal().reveal(".header__image", { delay: 700, origin: "right" });

// About section animations
ScrollReveal().reveal(".about__content h2", { delay: 200, origin: "left" });
ScrollReveal().reveal(".about__content p", { delay: 300, origin: "left" });
ScrollReveal().reveal(".about__btn", { delay: 400, origin: "left" });
ScrollReveal().reveal(".about__image", { delay: 500, origin: "right" });

// Features section animations
ScrollReveal().reveal(".features__header h2", { delay: 200, origin: "top" });
ScrollReveal().reveal(".features__header p", { delay: 300, origin: "top" });
ScrollReveal().reveal(".feature__card", {
  delay: 200,
  origin: "bottom",
  interval: 200,
});

// Stats section animations
ScrollReveal().reveal(".stat__item", {
  delay: 200,
  origin: "bottom",
  interval: 100,
});

// Speakers section animations
ScrollReveal().reveal(".speakers__header h2", { delay: 200, origin: "top" });
ScrollReveal().reveal(".speaker__card", {
  delay: 300,
  origin: "bottom",
  interval: 200,
});

// Footer animations
// ScrollReveal().reveal(".footer__container", {
//   delay: 200,
//   origin: "bottom",
//   duration: 1000,
// });

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute("href"));
    if (target) {
      target.scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    }
  });
});

// Add loading animation
window.addEventListener("load", function () {
  document.body.classList.add("loaded");
});

// Counter animation for stats
function animateCounter(element, target, duration = 2000) {
  let start = 0;
  const increment = target / (duration / 16);

  function updateCounter() {
    start += increment;
    if (start < target) {
      element.textContent = Math.floor(start) + (target >= 100 ? "%" : "+");
      requestAnimationFrame(updateCounter);
    } else {
      element.textContent = target + (target >= 100 ? "%" : "+");
    }
  }

  updateCounter();
}

// Intersection Observer for counter animation
const observerOptions = {
  threshold: 0.5,
  rootMargin: "0px 0px -100px 0px",
};

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      const statNumbers = entry.target.querySelectorAll(".stat__number");
      statNumbers.forEach((stat) => {
        const text = stat.textContent;
        const number = parseInt(text.replace(/\D/g, ""));
        if (number && !stat.classList.contains("animated")) {
          stat.classList.add("animated");
          animateCounter(stat, number, 2000);
        }
      });
    }
  });
}, observerOptions);

// Observe stats section
const statsSection = document.querySelector(".stats__container");
if (statsSection) {
  observer.observe(statsSection);
}

// Add parallax effect to background
window.addEventListener("scroll", () => {
  const scrolled = window.pageYOffset;
  const parallax = document.querySelector("body");
  const speed = scrolled * 0.5;

  if (parallax) {
    parallax.style.backgroundPosition = `center ${speed}px`;
  }
});

// Add hover effects for feature cards
document.querySelectorAll(".feature__card").forEach((card) => {
  card.addEventListener("mouseenter", function () {
    this.style.transform = "translateY(-10px) scale(1.02)";
  });

  card.addEventListener("mouseleave", function () {
    this.style.transform = "translateY(0) scale(1)";
  });
});

// Add click animation for buttons
document.querySelectorAll(".btn").forEach((button) => {
  button.addEventListener("click", function (e) {
    // Create ripple effect
    const ripple = document.createElement("span");
    const rect = this.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = e.clientX - rect.left - size / 2;
    const y = e.clientY - rect.top - size / 2;

    ripple.style.width = ripple.style.height = size + "px";
    ripple.style.left = x + "px";
    ripple.style.top = y + "px";
    ripple.classList.add("ripple");

    this.appendChild(ripple);

    setTimeout(() => {
      ripple.remove();
    }, 600);
  });
});

// Add CSS for ripple effect
const style = document.createElement("style");
style.textContent = `
  .btn {
    position: relative;
    overflow: hidden;
  }
  
  .ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.6);
    transform: scale(0);
    animation: ripple-animation 0.6s linear;
    pointer-events: none;
  }
  
  @keyframes ripple-animation {
    to {
      transform: scale(4);
      opacity: 0;
    }
  }
  
  .loaded {
    opacity: 1;
  }
  
  body {
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
  }
`;
document.head.appendChild(style);

console.log("🚀 Webinar page loaded successfully!");
