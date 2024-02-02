var seats = new Map();
var row = ["A", "B", "C", "D", "E", "F", "G", "H"];

document.addEventListener("DOMContentLoaded", function () {
  adminPage(); ///
  addToCheckOut();
  submitForm(); //
  switchTabs();
  toggleSeats();
});

function adminPage() {
  const uploadNewMovie = document.getElementById("upload-new-movie-form");
  const createDbMovie = document.getElementById("submit-button-create-movie");
  const createNewMovie = document.getElementById(
    "submit-button-create-new-movie"
  );

  if (createDbMovie) {
    createDbMovie.addEventListener("click", function (event) {
      console.log("submit");
      intializeDb.submit();
    });
  }
  if (createNewMovie) {
    createNewMovie.addEventListener("click", function (event) {
      console.log("submit");
      uploadNewMovie.submit();
    });
  }
}

function addToCheckOut() {
  const allSeats = document.getElementsByClassName("seat");
  checkout = document.getElementById("checkout-seats");
  let selectedSeatCount = 0;

  for (let i = 0; i < allSeats.length; i++) {
    allSeats[i].addEventListener("click", function () {
      const seatChoosen = document.createElement("div");
      seatChoosen.id = allSeats[i].id + "choosen";
      seatChoosen.className = "seatChoosen";

      allSeats[i].classList.toggle("selected");

      if (allSeats[i].classList.contains("selected")) {
        selectedSeatCount++;
        checkout.appendChild(seatChoosen);
        seatChoosen.innerText = allSeats[i].id;
      } else {
        selectedSeatCount--;
        const existingSeat = document.getElementById(seatChoosen.id);
        if (existingSeat) {
          checkout.removeChild(existingSeat);
        }
      }
      console.log("Selected seat count: " + selectedSeatCount / 2);
    });
  }
}

function submitForm() {
  const postForm = document.getElementById("selectedCheckBoxForm");
  const submitButton = document.getElementById("checkout-button");
  if (submitButton) {
    submitButton.addEventListener("click", function () {
      console.log("submit");
      postForm.submit();
    });
  }
}

function toggleSeats() {
  var seats = document.querySelectorAll(".seat");
  seats.forEach(function (seat) {
    seat.addEventListener("click", function () {
      this.classList.toggle("selected-seat");
    });
  });
}

function switchTabs() {
  const tabs = document.querySelectorAll("[data-tab-target]");
  const tabContents = document.querySelectorAll("[data-tab-content]");

  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      const target = document.querySelector(tab.dataset.tabTarget);
      tabContents.forEach((tabContent) => {
        tabContent.classList.remove("active");
      });
      tabs.forEach((tab) => {
        tab.classList.remove("active");
      });
      tab.classList.add("active");
      target.classList.add("active");
    });
  });
}

function addtocart() {
  alert("Add to cart!");
}
