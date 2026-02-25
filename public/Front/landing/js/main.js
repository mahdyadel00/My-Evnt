function startCountdown() {
  // Set the target date for the countdown
  const targetDate = new Date("October 29, 2024 20:00:00").getTime();

  // Update the countdown every second
  const interval = setInterval(() => {
    const now = new Date().getTime();
    const distance = targetDate - now;

    if (distance < 0) {
      clearInterval(interval);
      document.querySelector(".countdown-container").innerHTML =
        "<h5>The Event Has Already Started .</h5>";
      return;
    }

    // Calculate time (days, hours, minutes, seconds)
    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor(
      (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
    );
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    // Display results in the HTML
    document.getElementById("days").innerHTML = days;
    document.getElementById("hours").innerHTML = hours;
    document.getElementById("minutes").innerHTML = minutes;
    document.getElementById("seconds").innerHTML = seconds;
  }, 500);
}

// Start the countdown
startCountdown();

// Select the match-result section
const matchResultSection = document.getElementById("matchResult");

// Create an IntersectionObserver
const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      // Add a class to trigger the animations when the section is in view
      entry.target.classList.add("animate");
    }
  });
});

// Observe the match-result section
observer.observe(matchResultSection);
