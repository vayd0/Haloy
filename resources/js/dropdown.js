document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById("dropdown-toggle");
    const menu = document.getElementById("dropdown-menu");

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
});