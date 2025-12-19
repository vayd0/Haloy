document.addEventListener("DOMContentLoaded", () => {
    const burger = document.getElementById("nav-hamburger");
    const menu = document.getElementById("nav-mobile-menu");
    const close = document.getElementById("nav-mobile-close");

    function openMenu() {
        menu.classList.remove("pointer-events-none", "translate-x-[110%]", "opacity-0");
        menu.classList.add("pointer-events-auto", "translate-x-0", "opacity-100");
    }

    function closeMenu() {
        menu.classList.remove("translate-x-0", "opacity-100", "pointer-events-auto");
        menu.classList.add("translate-x-[110%]");
        menu.addEventListener("transitionend", handleTransitionEnd);
    }

    function handleTransitionEnd(e) {
        if (e.propertyName === "transform") {
            menu.classList.add("pointer-events-none", "opacity-0");
            menu.removeEventListener("transitionend", handleTransitionEnd);
        }
    }

    if (burger && menu) {
        burger.addEventListener("click", (e) => {
            e.stopPropagation();
            openMenu();
        });

        if (close) {
            close.addEventListener("click", (e) => {
                e.stopPropagation();
                closeMenu();
            });
        }

        document.addEventListener("click", (e) => {
            if (!menu.contains(e.target) && !burger.contains(e.target)) {
                closeMenu();
            }
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") closeMenu();
        });
    }
});