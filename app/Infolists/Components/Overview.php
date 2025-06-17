<?php

namespace App\Infolists\Components;

use Filament\Infolists\Components\Component;

class Overview extends Component
{

    protected string $view = 'infolists.components.overview';
    protected string $icon = 'heroicon-o-document-text';
    protected string | null $description = null;
    public static function make(): static
    {
        return app(static::class);
    }
}
