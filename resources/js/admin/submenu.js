export function toggleSubmenu() {
    document.addEventListener("DOMContentLoaded", function () {
        const submenuTriggers = document.querySelectorAll(".side-menu .has-submenu");

        // Restaurar estado salvo
        const savedOpenId = localStorage.getItem("openSubmenuId");
        if (savedOpenId) {
            const savedLi = document.querySelector(`.side-menu li[data-submenu-id="${savedOpenId}"]`);
            if (savedLi) {
                savedLi.classList.add("open");
                const icon = savedLi.querySelector("#submenu-icon");
                if (icon) {
                    icon.classList.remove("fa-plus");
                    icon.classList.add("fa-minus");
                }
            }
        }

        submenuTriggers.forEach(trigger => {
            trigger.addEventListener("click", function (e) {
                e.preventDefault();

                const li = this.parentElement;
                const icon = this.querySelector("#submenu-icon");

                // Fechar todos os outros submenus
                document.querySelectorAll(".side-menu li.open").forEach(openLi => {
                    if (openLi !== li) {
                        openLi.classList.remove("open");
                        const openIcon = openLi.querySelector("#submenu-icon");
                        if (openIcon) {
                            openIcon.classList.remove("fa-minus");
                            openIcon.classList.add("fa-plus");
                        }
                    }
                });

                // Alternar submenu atual
                li.classList.toggle("open");

                if (li.classList.contains("open")) {
                    icon.classList.remove("fa-plus");
                    icon.classList.add("fa-minus");
                    // salvar id do submenu aberto
                    localStorage.setItem("openSubmenuId", li.dataset.submenuId);
                } else {
                    icon.classList.remove("fa-minus");
                    icon.classList.add("fa-plus");
                    // limpar estado salvo
                    localStorage.removeItem("openSubmenuId");
                }
            });
        });
    });
}