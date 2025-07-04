// Assets/js/main.js

// --- FOCUS/BLUR en inputs (tu código existente) ---
const inputs = document.querySelectorAll(".input");
function addcl() {
  let parent = this.parentNode.parentNode;
  parent.classList.add("focus");
}
function remcl() {
  let parent = this.parentNode.parentNode;
  if (this.value == "") parent.classList.remove("focus");
}
inputs.forEach(input => {
  input.addEventListener("focus", addcl);
  input.addEventListener("blur", remcl);
});

// --- Toggle de submenús “treeview” ---
document.addEventListener("DOMContentLoaded", function() {
  const tvLinks = document.querySelectorAll('.app-menu__item[data-toggle="treeview"]');
  tvLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      const parentLi = this.closest('.treeview');
      parentLi.classList.toggle('is-expanded');
    });
  });
});
