export function daisyUIModalAnimations() {

    window.addEventListener('open-modal', event => {
        const modalId = event.detail.id;
        const modal = document.getElementById(modalId);

        if (modal) {
            modal.showModal();
        }
    });

    window.addEventListener('close-modal', event => {
        const modalId = event.detail.id;
        const modal = document.getElementById(modalId);

        if (modal) {
            modal.close();
        }
    });

    window.addEventListener('modal-cleanup', () => {
        setTimeout(() => {
            Livewire.dispatch('cleanup-now');
        }, 150);
    });
}