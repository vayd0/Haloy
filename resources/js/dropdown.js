document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.getElementById("dropdown-toggle");
  const menu = document.getElementById("dropdown-menu");

  const toggle2 = document.getElementById("dropdown-toggle2");
  const menu2 = document.getElementById("dropdown-menu2");
  if (toggle && menu) {
    toggle.addEventListener("click", (e) => {
      e.stopPropagation();
      const isOpen = menu.classList.contains("opacity-100");
      if (isOpen) {
        menu.classList.remove("opacity-100", "pointer-events-auto");
        menu.classList.add("opacity-0", "pointer-events-none");
      } else {
        menu.classList.remove("opacity-0", "pointer-events-none");
        menu.classList.add("opacity-100", "pointer-events-auto");
      }
    });

    document.addEventListener("click", (e) => {
      if (!menu.contains(e.target) && !toggle.contains(e.target)) {
        menu.classList.remove("opacity-100", "pointer-events-auto");
        menu.classList.add("opacity-0", "pointer-events-none");
      }
    });
  }

    if (toggle2 && menu2) {
    toggle2.addEventListener("click", (e) => {
      e.stopPropagation();
      const isOpen = menu2.classList.contains("opacity-100");
      if (isOpen) {
        menu2.classList.remove("opacity-100", "pointer-events-auto");
        menu2.classList.add("opacity-0", "pointer-events-none");
      } else {
        menu2.classList.remove("opacity-0", "pointer-events-none");
        menu2.classList.add("opacity-100", "pointer-events-auto");
      }
    });

    document.addEventListener("click", (e) => {
      if (!menu2.contains(e.target) && !toggle2.contains(e.target)) {
        menu2.classList.remove("opacity-100", "pointer-events-auto");
        menu2.classList.add("opacity-0", "pointer-events-none");
      }
    });
  }
});
