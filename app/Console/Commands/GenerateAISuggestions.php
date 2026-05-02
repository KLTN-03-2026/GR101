<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateAISuggestions extends Command
{
    /**
     * The name and signature of the console command.\\
     *
     * @var string
     */
    protected $signature = 'ai:generate-suggestions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate AI suggestions for revenue optimization';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating AI suggestions...');

        $service = new \App\Services\AISuggestionService();
        $service->generateAllSuggestions();

        $this->info('AI suggestions generated successfully!');

        return Command::SUCCESS;
    }
}
