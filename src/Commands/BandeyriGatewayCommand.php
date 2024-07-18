<?php

namespace Javaabu\BandeyriGateway\Commands;

use Illuminate\Console\Command;

class BandeyriGatewayCommand extends Command
{
    public $signature = 'bandeyri-gateway';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
