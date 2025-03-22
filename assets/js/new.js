function toggleDetails(btn) {
  let details = btn.nextElementSibling; // Get the corresponding details div
  if (details.style.maxHeight && details.style.maxHeight !== "0px") {
    // Collapse the details
    details.style.maxHeight = "0px";
    details.style.opacity = "0";
    details.style.visibility = "hidden";
    btn.innerHTML = 'Read More <i class="ti-plus"></i>';
  } else {
    // Expand the details
    details.style.maxHeight = details.scrollHeight + "px";
    details.style.opacity = "1";
    details.style.visibility = "visible";
    btn.innerHTML = 'Read Less <i class="ti-minus"></i>';
  }
}
