/* YLCD-ASBL — Comportements de l'espace connecté et des pages d'authentification */
(function () {
  "use strict";

  // Menu latéral sur mobile
  document.querySelectorAll("[data-sidebar-toggle]").forEach(function (btn) {
    btn.addEventListener("click", function () { document.body.classList.toggle("sidebar-open"); });
  });
  document.querySelectorAll("[data-sidebar-close]").forEach(function (el) {
    el.addEventListener("click", function () { document.body.classList.remove("sidebar-open"); });
  });

  // Confirmation avant suppression / action sensible
  document.querySelectorAll("form[data-confirm]").forEach(function (form) {
    form.addEventListener("submit", function (e) {
      if (!window.confirm(form.getAttribute("data-confirm"))) e.preventDefault();
    });
  });

  // Changement de rôle : envoi automatique (avec confirmation, sinon on rétablit la valeur)
  document.querySelectorAll("select[data-autosubmit]").forEach(function (select) {
    var initial = select.value;
    select.addEventListener("change", function () {
      var form = select.form;
      var msg = form.getAttribute("data-confirm");
      if (msg && !window.confirm(msg)) { select.value = initial; return; }
      form.submit();
    });
  });

  // Afficher / masquer un mot de passe
  document.querySelectorAll(".toggle-password").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var input = document.getElementById(btn.getAttribute("data-target"));
      if (!input) return;
      input.type = input.type === "password" ? "text" : "password";
      btn.innerHTML = input.type === "password" ? '<i class="fa-regular fa-eye"></i>' : '<i class="fa-regular fa-eye-slash"></i>';
    });
  });

  function initials(name) {
    var parts = (name || "").trim().split(/[\s._-]+/).filter(Boolean);
    if (!parts.length) return "?";
    if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
    return parts[0].slice(0, 2).toUpperCase();
  }

  // Inscription / configuration : aperçu photo ou initiales en direct
  document.querySelectorAll(".photo-preview").forEach(function (preview) {
    var form = preview.closest("form");
    var fileInput = form && form.querySelector(".photo-input");
    var nameInput = form && form.querySelector('[name="' + preview.getAttribute("data-initials-from") + '"]');
    var hasImage = !!preview.querySelector("img");
    if (nameInput) {
      nameInput.addEventListener("input", function () {
        if (!hasImage) preview.innerHTML = "<span>" + initials(nameInput.value) + "</span>";
      });
    }
    if (fileInput) {
      fileInput.addEventListener("change", function () {
        if (fileInput.files && fileInput.files[0]) {
          hasImage = true;
          preview.innerHTML = '<img src="' + URL.createObjectURL(fileInput.files[0]) + '" alt="">';
        }
      });
    }
  });

  // Champ image unique : aperçu
  document.querySelectorAll("input[data-single-preview]").forEach(function (input) {
    input.addEventListener("change", function () {
      var target = document.querySelector(input.getAttribute("data-single-preview"));
      if (target && input.files && input.files[0]) {
        target.innerHTML = '<img src="' + URL.createObjectURL(input.files[0]) + '" alt="">';
      }
    });
  });

  // Médias multiples d'une activité : aperçu + glisser-déposer
  document.querySelectorAll("input[data-preview]").forEach(function (input) {
    var target = document.querySelector(input.getAttribute("data-preview"));
    var zone = input.closest(".dropzone");

    function render() {
      if (!target) return;
      target.innerHTML = "";
      Array.prototype.forEach.call(input.files, function (file) {
        var url = URL.createObjectURL(file);
        var item = document.createElement("div");
        item.className = "media-item";
        item.innerHTML = (file.type.indexOf("video") === 0
          ? '<video src="' + url + '" muted></video><span class="media-type"><i class="fa-solid fa-video"></i></span>'
          : '<img src="' + url + '" alt="">') +
          '<div class="media-new">' + (input.getAttribute("data-new-label") || "+") + " " + file.name.replace(/</g, "&lt;") + "</div>";
        target.appendChild(item);
      });
    }
    input.addEventListener("change", render);

    if (zone) {
      ["dragenter", "dragover"].forEach(function (ev) {
        zone.addEventListener(ev, function (e) { e.preventDefault(); zone.classList.add("dragover"); });
      });
      ["dragleave", "drop"].forEach(function (ev) {
        zone.addEventListener(ev, function (e) { e.preventDefault(); zone.classList.remove("dragover"); });
      });
      zone.addEventListener("drop", function (e) {
        if (e.dataTransfer && e.dataTransfer.files.length) {
          input.files = e.dataTransfer.files;
          render();
        }
      });
    }
  });

  // Les messages flash disparaissent après quelques secondes
  setTimeout(function () {
    document.querySelectorAll(".flash-stack .alert-success").forEach(function (a) {
      a.classList.remove("show");
      setTimeout(function () { a.remove(); }, 300);
    });
  }, 5000);
})();
