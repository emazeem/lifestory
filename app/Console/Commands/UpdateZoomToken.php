<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use App\Traits\ZoomOAuthTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateZoomToken extends Command
{
    use ZoomOAuthTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-zoom-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->update_token();
        } catch (\Exception $e) {
            Log::error('Error occurred during token update:', ['exception' => $e->getMessage()]);
            return; 
        }
    }
}
