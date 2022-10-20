<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendToFacebook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facebook:share';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Share articles to Facebook page';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return Command::SUCCESS;
    }
}
