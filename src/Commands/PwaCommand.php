<?php

namespace Foxws\Pwa\Commands;

use Illuminate\Console\Command;

class PwaCommand extends Command
{
    public $signature = 'laravel-pwa';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
