document.querySelectorAll(".deletedBtn").forEach((btn) => {
  btn.addEventListener("click", (event) => {
    if (!confirm("Do you want to delete it?")) {
      event.preventDefault(); // Prevent the link from being followed
    }
  });
});

// Modal functionality
var modal = document.getElementById("myModal");
var btn = document.getElementById("openModal");
var span = document.getElementsByClassName("close")[0];

btn.onclick = function () {
  modal.style.display = "block";
};

span.onclick = function () {
  modal.style.display = "none";
};

window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
};

// Add this to your existing script section
// Update Modal functionality
var updateModal = document.getElementById("updateModal");
var updateBtns = document.querySelectorAll(".updateBtn");
var updateClose = document.getElementsByClassName("close-update")[0];
var updateForm = document.getElementById("updateForm");

// Add click event to all update buttons
updateBtns.forEach((btn) => {
  btn.addEventListener("click", function (e) {
    e.preventDefault();
    updateModal.style.display = "block";

    // Get data from data attributes
    const id = this.getAttribute("data-id");
    const name = this.getAttribute("data-name");
    const category = this.getAttribute("data-category");
    const price = this.getAttribute("data-price");

    // Set form action
    updateForm.action = `/updatedetails/${id}`;

    // Fill form fields
    document.getElementById("updatename").value = name;
    document.getElementById("updatecat").value = category;
    document.getElementById("updateprice").value = price;
    document.getElementById("updateId").value = id;
  });
});

// Close button functionality
updateClose.onclick = function () {
  updateModal.style.display = "none";
};

// Click outside modal to close
window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
  if (event.target == updateModal) {
    updateModal.style.display = "none";
  }
};

document.getElementById("black").addEventListener("click", () => {
  confirm("Do you want to logout?");
});

var filtermodal = document.getElementById("filterModal");