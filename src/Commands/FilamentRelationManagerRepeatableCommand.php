<?php

namespace Zvizvi\FilamentRelationManagerRepeatable\Commands;

use Illuminate\Console\Command;

class FilamentRelationManagerRepeatableCommand extends Command
{
    public $signature = 'filament-relation-manager-repeatable';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
