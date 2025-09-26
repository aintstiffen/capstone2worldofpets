<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanupTempUploads extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'uploads:cleanup {--hours=24 : Hours after which temp files are deleted}';

    /**
     * The console command description.
     */
    protected $description = 'Clean up old temporary upload files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = $this->option('hours');
        $cutoff = Carbon::now()->subHours($hours);
        
        $disk = Storage::disk(config('livewire.temporary_file_upload.disk'));
        $directory = config('livewire.temporary_file_upload.directory');
        
        if (!$disk->exists($directory)) {
            $this->info('Temporary upload directory does not exist.');
            return 0;
        }
        
        $files = $disk->files($directory);
        $deletedCount = 0;
        
        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp($disk->lastModified($file));
            
            if ($lastModified->lt($cutoff)) {
                $disk->delete($file);
                $deletedCount++;
            }
        }
        
        $this->info("Deleted {$deletedCount} temporary upload files older than {$hours} hours.");
        
        return 0;
    }
}