<?php

namespace WebFresh\UserManager\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('wfom:install')]
#[Description('Command description')]
class WfomInstallCommand extends Command
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
