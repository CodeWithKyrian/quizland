<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearTempUploadsCommand extends Command
{
    protected $signature = 'temp:clear';

    protected $description = 'Clear temporary uploads';

    public function handle(): void
    {
        $this->info('Clearing temporary uploads...');

        $files = \Storage::disk('temp')->files();

        \Storage::disk('temp')->delete($files);

        $this->info('Temporary uploads cleared!');
    }
}
