<?php

namespace App\Console\Commands;

use App\Models\Story;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MoveFileToS3Command extends Command
{
    protected $signature = 'move-file-to-s3';
    protected $description = 'Move file from local "public" disk to S3';

    public function handle()
    {
        if (config('app.switch_storage') != 'local') {
            try {
                ini_set('memory_limit', '2048M');
                ini_set('post_max_size', '2048M');
                ini_set('upload_max_filesize', '2048M');
                ini_set('max_execution_time', 900);

                $oldestStory = Story::where("stored_at", "public")->orderBy("created_at", "ASC")->first();

                if ($oldestStory) {
                    $fileName = '/session_recordings/' . $oldestStory->customer->id . '/' . $oldestStory->name;
                    $thumbnailName = '/session_recordings/' . $oldestStory->customer->id . '/' . $oldestStory->thumbnail;

                    if (Storage::disk('public')->exists($fileName)) {
                        $fileContents = Storage::disk('public')->get($fileName);
                        $metadata = [
                            'ContentType' => 'video/mp4', // Set the Content-Type header
                            'ContentDisposition' => 'attachment; filename="' . $fileName . '"', // Set the Content-Disposition header
                        ];

                        if (Storage::disk('s3')->put($fileName, $fileContents, $metadata)) {
                            $newPath = Storage::disk('s3')->url($fileName);
                            $oldestStory->file = $newPath;
                            $oldestStory->stored_at = "s3";
                            $oldestStory->save();

                            // Delete the file from the 'public' disk
                            Storage::disk('public')->delete($fileName);

                            $this->info('File moved to S3 successfully.');
                        } else {
                            $this->error('Failed to upload the file to S3.');
                        }
                    } else {
                        $this->error('File does not exist in the "public" disk.');
                    }
                    if (Storage::disk('public')->exists($thumbnailName)) {
                        $thumbnailContents = Storage::disk('public')->get($thumbnailName);

                        if (Storage::disk('s3')->put($thumbnailName, $thumbnailContents)) {
                            $thumbnailPath = Storage::disk('s3')->url($thumbnailName);
                            $oldestStory->thumbnail = $thumbnailPath;
                            $oldestStory->save();

                            // Delete the file from the 'public' disk
                            Storage::disk('public')->delete($thumbnailName);

                            $this->info('thumbnailName moved to S3 successfully.');
                        } else {
                            $this->error('Thumbnail failed to upload to S3.');
                        }
                    } else {
                        $this->error('Thumbnail does not exist in the "public" disk.');
                    }
                } else {
                    $this->info('No eligible stories found to move to S3.');
                }
            } catch (\Exception $e) {
                $this->error('An error occurred: ' . $e->getMessage());
            }
        }
    }
}
