<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MLResumeParser;

class TrainResumeParser extends Command
{
    protected $signature = 'resume-parser:train';
    protected $description = 'Train the resume parser model';

    public function handle()
    {
        $parser = new MLResumeParser();
        
        // Sample training data (you should replace with real labeled resumes)
    
        
        $labels = [
            'skills',
            'experience',
            'education',
            'skills',
            'experience',
            'education'
        ];
        
        $parser->trainModel($samples, $labels);
        
        $this->info('Resume parser model trained successfully!');
    }
}