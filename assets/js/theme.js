/* YLCD-ASBL — Bascule mode clair / sombre (mémorisée dans un cookie lu par PHP, clair par défaut) */
(function () {
  "use strict";
  var root = document.documentElement;

  function apply(theme) {
    root.setAttribute("data-theme", theme);
    document.cookie = "theme=" + theme + ";path=/;max-age=31536000;samesite=lax";
    document.querySelectorAll("[data-theme-toggle]").forEach(function (btn) {
      var dark = theme === "dark";
      btn.setAttribute("aria-pressed", dark ? "true" : "false");
      btn.title = dark ? btn.getAttribute("data-label-light") : btn.getAttribute("data-label-dark");
      var icon = btn.querySelector("i");
      if (icon) icon.className = "fa-solid " + (dark ? "fa-sun" : "fa-moon");
    });
  }

  document.querySelectorAll("[data-theme-toggle]").forEach(function (btn) {
    btn.addEventListener("click", function () {
      apply(root.getAttribute("data-theme") === "dark" ? "light" : "dark");
    });
  });
})();
