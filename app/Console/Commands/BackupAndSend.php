<?php

namespace App\Console\Commands;

use App\Http\Helpers\Services\TelegramService;
use Illuminate\Console\Command;
use Spatie\DbDumper\Databases\MySql;

class BackupAndSend extends Command
{
    protected $signature = 'backup:send-telegram';
    protected $description = 'Backup database and send to Telegram';
    protected TelegramService $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        parent::__construct();
        $this->telegramService = $telegramService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Backup faylini yaratish
        $fileName = 'backup-' . date('Y-m-d_H-i-s') . '.sql';
        $filePath = storage_path('app/' . $fileName);

        MySql::create()
            ->setDbName(env('DB_DATABASE'))
            ->setUserName(env('DB_USERNAME'))
            ->setPassword(env('DB_PASSWORD'))
            ->dumpToFile($filePath);

        // Faylni Telegramga yuborish
        $this->telegramService->sendDocument($filePath);

        // Faylni o'chirish
        unlink($filePath);

        $this->info('Backup created and sent to Telegram successfully.');
    }
}
