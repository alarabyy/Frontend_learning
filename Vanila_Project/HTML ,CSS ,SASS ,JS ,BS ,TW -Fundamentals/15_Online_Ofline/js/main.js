const title = document.querySelector(".title");
const ul = document.querySelector("ul");
const reload = document.querySelector(".reload");

//================================================================

window.onload = function() {
  if (window.navigator.onLine) {
    online();
  } else {
    offline();
  }
};
//================================================================
function online() {
  title.innerHTML = "Online Now";
  title.style.color = "green";
  ul.classList.add("hide");
  reload.classList.add("hide");
}
//================================================================
function offline() {
  title.innerHTML = "Offline Now";
  title.style.color = "red";
  ul.classList.remove("hide");
  reload.classList.remove("hide");
}
//================================================================

window.addEventListener("online", function() {
  online();
});

window.addEventListener("offline", function() {
  offline();
});
