<?php

use App\Console\Commands\EmailDatabaseBackup;
use App\Mail\DatabaseBackupMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Tester\CommandTester;

beforeEach(function () {
    Mail::fake();
    Storage::fake('local');

    config()->set('app.name', 'Dregozone');
    config()->set('backup.backup.name', 'site-backups');
    config()->set('backup.mail.recipient', 'backups@example.com');
});

function runEmailDatabaseBackupCommand(int $backupResult = Command::SUCCESS): array
{
    $command = new class($backupResult) extends EmailDatabaseBackup
    {
        public ?string $calledCommand = null;

        public array $calledArguments = [];

        public function __construct(private int $backupResult)
        {
            parent::__construct();
        }

        public function call($command, array $arguments = [])
        {
            $this->calledCommand = is_string($command) ? $command : $command->getName();
            $this->calledArguments = $arguments;

            return $this->backupResult;
        }
    };

    $command->setLaravel(app());

    $tester = new CommandTester($command);
    $status = $tester->execute([]);

    return [$command, $tester, $status];
}

function fakeBackupFile(string $directory, string $filename, int $timestamp): string
{
    $disk = Storage::disk('local');
    $path = $directory.'/'.$filename;

    $disk->put($path, 'backup-contents-'.$filename);
    touch($disk->path($path), $timestamp);
    clearstatcache(true, $disk->path($path));

    return $path;
}

test('it sends the most recent backup file from the configured backup directory', function () {
    $disk = Storage::disk('local');

    fakeBackupFile('site-backups', 'older-backup.zip', time() - 120);
    $latestPath = fakeBackupFile('site-backups', 'latest-backup.zip', time());

    [$command, $tester, $status] = runEmailDatabaseBackupCommand();

    expect($status)->toBe(Command::SUCCESS);
    expect($command->calledCommand)->toBe('backup:run');
    expect($command->calledArguments)->toBe(['--only-db' => true]);

    Mail::assertSent(DatabaseBackupMail::class, function (DatabaseBackupMail $mail) use ($disk, $latestPath) {
        $mail->assertTo('backups@example.com');
        $mail->assertHasAttachment($disk->path($latestPath), [
            'as' => 'latest-backup.zip',
            'mime' => 'application/zip',
        ]);

        return true;
    });

    expect($tester->getDisplay())->toContain('Database backup [latest-backup.zip] was emailed to backups@example.com.');
});

test('it fails when no backup files exist', function () {
    [$command, $tester, $status] = runEmailDatabaseBackupCommand();

    expect($status)->toBe(Command::FAILURE);
    expect($command->calledCommand)->toBe('backup:run');
    Mail::assertNothingSent();
    expect($tester->getDisplay())->toContain('No backup files were found in ['.Storage::disk('local')->path('site-backups').'].');
});

test('it fails when no recipient is configured', function () {
    config()->set('backup.mail.recipient', null);

    fakeBackupFile('site-backups', 'latest-backup.zip', time());

    [$command, $tester, $status] = runEmailDatabaseBackupCommand();

    expect($status)->toBe(Command::FAILURE);
    expect($command->calledCommand)->toBe('backup:run');
    Mail::assertNothingSent();
    expect($tester->getDisplay())->toContain('No backup recipient is configured.');
});

test('it uses the configured backup name instead of the app name', function () {
    $disk = Storage::disk('local');

    config()->set('app.name', 'App Name Directory');
    config()->set('backup.backup.name', 'custom-backup-directory');

    fakeBackupFile('App Name Directory', 'ignored-backup.zip', time() - 120);
    $latestPath = fakeBackupFile('custom-backup-directory', 'configured-backup.zip', time());

    [$command, $tester, $status] = runEmailDatabaseBackupCommand();

    expect($status)->toBe(Command::SUCCESS);
    expect($command->calledArguments)->toBe(['--only-db' => true]);

    Mail::assertSent(DatabaseBackupMail::class, function (DatabaseBackupMail $mail) use ($disk, $latestPath) {
        $mail->assertHasAttachment($disk->path($latestPath), [
            'as' => 'configured-backup.zip',
            'mime' => 'application/zip',
        ]);

        return true;
    });

    expect($tester->getDisplay())->toContain('configured-backup.zip');
});

test('it fails when the nested backup command fails', function () {
    [$command, $tester, $status] = runEmailDatabaseBackupCommand(Command::FAILURE);

    expect($status)->toBe(Command::FAILURE);
    expect($command->calledCommand)->toBe('backup:run');
    expect($command->calledArguments)->toBe(['--only-db' => true]);
    Mail::assertNothingSent();
    expect($tester->getDisplay())->toContain('The backup:run command failed, so no email was sent.');
});
