<?php

namespace WebFresh\UserManager\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('wfum:install')]
#[Description('Command description')]
class WfumInstallCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Installing BlogPackage...');

        $this->info('Installation completed, enjoy!');
        //
    }
}
