// Gallery Section

const images = [
  { src: "./images/g_pic.png" },
  { src: "./images/g_pic_1.png" },
  { src: "./images/g_pic_2.png" },
  { src: "./images/g_pic_3.png" },
  { src: "./images/g_pic_4.png" },
];
let currentIndex = 0;

// Function to open the modal
function openModal(index) {
  currentIndex = index;
  const modal = document.getElementById("modal");
  const modalImage = document.getElementById("modal-image");
  showImage(index);

  // Show the modal
  modal.style.display = "block";
  modalImage.src = images[currentIndex].src;

  // Disable scrolling on the body
  document.body.style.overflow = "hidden";

  // Set navbar and other elements behind the modal
  document.querySelector(".top-header").style.zIndex = "0";
}

// Function to close the modal
function closeModal() {
  const modal = document.getElementById("modal");

  // Hide the modal
  modal.style.display = "none";

  // Enable scrolling on the body
  document.body.style.overflow = "auto";

  // Restore the navbar's z-index
  document.querySelector(".top-header").style.zIndex = "2000";
}

// Function to show a specific image in the modal and set active thumbnail
function showImage(index) {
  const modalImage = document.getElementById("modal-image");
  modalImage.src = images[index].src;

  // Update the active thumbnail
  const thumbnails = document.querySelectorAll(".thumbnail");
  thumbnails.forEach((thumbnail, i) => {
    thumbnail.classList.toggle("active", i === index);
  });
}

function changeImage(direction) {
  currentIndex += direction;
  if (currentIndex < 0) {
    currentIndex = images.length - 1;
  } else if (currentIndex >= images.length) {
    currentIndex = 0;
  }
  showImage(currentIndex);
}
