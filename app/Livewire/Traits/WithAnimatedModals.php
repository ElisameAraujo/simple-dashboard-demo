<?php

namespace App\Livewire\Traits;

use Livewire\Attributes\On;

trait WithAnimatedModals
{
    public $modalAction = null;

    public function openModal($action, $id = null)
    {
        $this->modalAction = $action;

        if (method_exists($this, 'loadModalData')) {
            $this->loadModalData($action, $id);
        }

        $this->dispatch('open-modal', id: $this->modalIdForAction($action));
    }

    public function requestCloseModal()
    {
        $this->dispatch('close-modal', id: $this->modalIdForAction($this->modalAction));
        $this->dispatch('modal-cleanup');
    }

    #[On('cleanup-now')]
    public function cleanupNow()
    {
        $this->modalAction = null;

        if (method_exists($this, 'resetModalData')) {
            $this->resetModalData();
        }
    }

    abstract protected function modalIdForAction($action);
}
