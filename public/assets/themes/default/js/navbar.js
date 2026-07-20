// Select all toggle elements and dropdowns
const toggles = document.querySelectorAll(".link_title");
const dropdowns = document.querySelectorAll(".dropdown_list");

// Apply radius_down to specific toggles initially
toggles.forEach((toggle) => {
  if (toggle.id === "toggleDropdown9" || toggle.id === "toggleDropdown13") {
    toggle.classList.add("radius_down"); // Initial radius_down for specific IDs
  }
});

// Add click event listeners to each toggle
toggles.forEach((toggle, index) => {
  const dropdown = dropdowns[index];
  const iconDown = toggle.querySelector("i.fa-angle-down");
  const iconUp = toggle.querySelector("i.fa-angle-up");

  // Handle click on the toggle to open/close the dropdown
  toggle.addEventListener("click", (e) => {
    // Prevent the document click handler from closing the dropdown when clicking on it
    e.stopPropagation();

    // Check if the current dropdown is already visible
    const isDropdownOpen = dropdown.style.display === "block";

    // First, hide all dropdowns and reset all icons
    dropdowns.forEach((d, i) => {
      d.style.display = "none"; // Hide all dropdowns
      const allIconsDown = toggles[i].querySelector("i.fa-angle-down");
      const allIconsUp = toggles[i].querySelector("i.fa-angle-up");
      allIconsDown.style.display = "inline"; // Reset all down arrows
      allIconsUp.style.display = "none"; // Reset all up arrows

      // Reapply radius_down to toggleDropdown9 and toggleDropdown13
      if (
        toggles[i].id === "toggleDropdown9" ||
        toggles[i].id === "toggleDropdown13"
      ) {
        toggles[i].classList.add("radius_down"); // Reapply radius_down on close
      }

      // Remove dropdown-open from toggleDropdown5 to toggleDropdown13
      if (parseInt(toggles[i].id.replace("toggleDropdown", ""), 10) >= 5 &&
          parseInt(toggles[i].id.replace("toggleDropdown", ""), 10) <= 13) {
        toggles[i].classList.remove("dropdown-open");
      }
    });

    // If the current dropdown was not open, display it
    if (!isDropdownOpen) {
      dropdown.style.display = "block";
      iconDown.style.display = "none"; // Hide the down arrow
      iconUp.style.display = "inline"; // Show the up arrow

      // Remove radius_down only when toggleDropdown9 or toggleDropdown13 is open
      if (toggle.id === "toggleDropdown9" || toggle.id === "toggleDropdown13") {
        toggle.classList.remove("radius_down");
      }

      // Add dropdown-open class to toggleDropdown5 to toggleDropdown13
      const toggleIdNumber = parseInt(toggle.id.replace("toggleDropdown", ""), 10);
      if (toggleIdNumber >= 5 && toggleIdNumber <= 13) {
        toggle.classList.add("dropdown-open");
      }
    }
  });
});

// Close all dropdowns if clicking outside of any dropdown
document.addEventListener("click", () => {
  dropdowns.forEach((dropdown, index) => {
    dropdown.style.display = "none"; // Hide all dropdowns
    const iconDown = toggles[index].querySelector("i.fa-angle-down");
    const iconUp = toggles[index].querySelector("i.fa-angle-up");
    iconDown.style.display = "inline"; // Show down arrow
    iconUp.style.display = "none"; // Hide up arrow

    // Reapply radius_down only for toggleDropdown9 and toggleDropdown13 on close
    if (
      toggles[index].id === "toggleDropdown9" ||
      toggles[index].id === "toggleDropdown13"
    ) {
      toggles[index].classList.add("radius_down");
    }

    // Remove dropdown-open from toggleDropdown5 to toggleDropdown13
    const toggleIdNumber = parseInt(toggles[index].id.replace("toggleDropdown", ""), 10);
    if (toggleIdNumber >= 5 && toggleIdNumber <= 13) {
      toggles[index].classList.remove("dropdown-open");
    }
  });
});

// Select the necessary elements
const menuOpenIcon = document.getElementById("menuOpenIcon");
const menuCloseIcon = document.getElementById("menuCloseIcon");
const menuItem = document.getElementById("menuItem");



// Get the navbar element
const navbar = document.querySelector('.navbar_ffs');

// Function to handle the scroll event
window.addEventListener('scroll', () => {
  // Check if the page is scrolled more than 50px (or any threshold you prefer)
  if (window.scrollY > 50) {
    navbar.classList.add('scrolled'); // Add the 'scrolled' class to change background and colors
  } else {
    navbar.classList.remove('scrolled'); // Remove the 'scrolled' class to revert background and colors
  }
});

