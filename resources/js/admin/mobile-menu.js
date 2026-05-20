export function toggleMenu() {
    document.addEventListener("DOMContentLoaded", function () {
        const openBtn = document.getElementById("open-mobile");
        const mobileMenu = document.getElementById("side-menu-mobile");
        const backdrop = document.querySelector(".side-menu-backdrop");
        const closeBtn = mobileMenu.querySelector(".close-b button");

        function openMenu() {
            mobileMenu.classList.add("open");
            backdrop.classList.add("open");
            document.body.classList.add("no-scroll"); // bloqueia scroll
        }

        function closeMenu() {
            mobileMenu.classList.remove("open");
            backdrop.classList.remove("open");
            document.body.classList.remove("no-scroll"); // libera scroll
        }

        openBtn.addEventListener("click", openMenu);
        closeBtn.addEventListener("click", closeMenu);
        backdrop.addEventListener("click", closeMenu);

        document.addEventListener("keydown", function (e) {
            if (e.key === "Escape") {
                closeMenu();
            }
        });
    });

}