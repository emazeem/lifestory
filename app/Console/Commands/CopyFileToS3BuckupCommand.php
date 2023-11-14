<?php

namespace App\Console\Commands;

use App\Models\Story;
use Aws\S3\S3Client;
use Illuminate\Console\Command;

class CopyFileToS3BuckupCommand extends Command
{
    protected $signature = 'copy-file-to-s3-backup';

    protected $description = 'Copy the customer video to backup 1 bucket';

    public function handle()
    {
        if (config('app.switch_storage') != 'local') {

            ini_set('memory_limit', '2048M');
            ini_set('post_max_size', '2048M');
            ini_set('upload_max_filesize', '2048M');
            ini_set('max_execution_time', 900);

            $oldestStory = Story::where("stored_at", "s3")->orderBy("created_at", "ASC")->whereNull("backup_1")->first();
            if ($oldestStory) {

                $s3Client = new S3Client([
                    'region' => env('AWS_BACKUP_REGION'),
                    'version' => 'latest',
                    'credentials' => [
                        'key' => env('AWS_ACCESS_KEY_ID'),
                        'secret' => env('AWS_SECRET_ACCESS_KEY'),
                    ],
                ]);

                $sourceBucket = env('AWS_BUCKET');
                $targetBucket = env('AWS_BUCKET_BACKUP');

                $sourceObjectKey = 'session_recordings/' . $oldestStory->customer->id . '/' . $oldestStory->name;
                $sourceObjectKeyThumbnail = 'session_recordings/' . $oldestStory->customer->id . '/' . $oldestStory->getScreenName();

                try {
                    $s3Client->copyObject([
                        'Bucket' => $targetBucket,
                        'CopySource' => "{$sourceBucket}/{$sourceObjectKey}",
                        'Key' => $sourceObjectKey,
                    ]);

                    $oldestStory->backup_1 = $s3Client->getObjectUrl($targetBucket, $sourceObjectKey);
                    $oldestStory->save();

                    $this->info('File Copied to S3 Backup successfully.');
                } catch (\Exception $e) {
                    $this->error('Error in Video Backup: ' . $e->getMessage());
                }

                try {
                    $s3Client->copyObject([
                        'Bucket' => $targetBucket,
                        'CopySource' => "{$sourceBucket}/{$sourceObjectKeyThumbnail}",
                        'Key' => $sourceObjectKeyThumbnail,
                        'CopySourceBucket' => $sourceBucket,
                    ]);

                    $this->info('Thumbnail Copied to S3 Backup successfully.');
                } catch (\Exception $e) {
                    $this->error('Error in Thumbnail Backup: ' . $e->getMessage());
                }

            } else {
                $this->info('No story found to copy to the backup bucket.');
            }
        }
    }
}
