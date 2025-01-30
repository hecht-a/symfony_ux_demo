<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class Alert
{
    use DefaultActionTrait;

    public ?string $type = null;
    public ?string $message = null;

    #[LiveAction]
    public function showError(): void
    {
        dump('ok');
        $this->type = 'danger';
        $this->message = 'An error occurred!';
    }

    #[LiveAction]
    public function showSuccess(): void
    {
        dump('ok');
        $this->type = 'success';
        $this->message = 'Operation successful!';
    }

    public function getIcon(): ?string
    {
        return match ($this->type) {
            'success' => 'bi:check-circle',
            'danger' => 'bi:exclamation-circle',
            default => null,
        };
    }
}
