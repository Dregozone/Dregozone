<?php

namespace App\Console\Commands;

use App\Mail\DatabaseBackupMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EmailDatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:email-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a database backup and email it via Resend';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->call('backup:run', ['--only-db' => true]) !== Command::SUCCESS) {
            $this->error('The backup:run command failed, so no email was sent.');

            return Command::FAILURE;
        }

        $backupDisk = Storage::disk('local');
        $backupDirectory = config('backup.backup.name', config('app.name'));
        $backupRecipient = config('backup.mail.recipient');
        $files = $backupDisk->files($backupDirectory);

        if (blank($backupRecipient)) {
            $this->error('No backup recipient is configured. Set BACKUP_RECIPIENT_EMAIL so backup.mail.recipient resolves to a valid email address.');

            return Command::FAILURE;
        }

        if ($files === []) {
            $this->error(sprintf('No backup files were found in [%s].', $backupDisk->path($backupDirectory)));

            return Command::FAILURE;
        }

        $latestFile = collect($files)
            ->sortByDesc(fn (string $file): int => $backupDisk->lastModified($file))
            ->first();

        if (! is_string($latestFile)) {
            $this->error(sprintf('Unable to determine the latest backup file in [%s].', $backupDisk->path($backupDirectory)));

            return Command::FAILURE;
        }

        $filename = basename($latestFile);
        $fullPath = $backupDisk->path($latestFile);

        // Resend currently supports attachments up to 40 MB per message.
        Mail::to($backupRecipient)->send(new DatabaseBackupMail($fullPath, $filename));

        $this->info(sprintf('Database backup [%s] was emailed to %s.', $filename, $backupRecipient));

        return Command::SUCCESS;
    }
}
