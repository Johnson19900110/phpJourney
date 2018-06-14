<?php

namespace App\Console\Commands;

use App\Libraries\CnblogsPostSpider;
use Goutte\Client;
use Illuminate\Console\Command;

class ImportPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import posts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $client = new Client();
        foreach (config('posts') as $url) {
            $spider = new CnblogsPostSpider($client, $url);
            $spider->getUrls();
            $this->info('create one blog!');
        }
    }
}
