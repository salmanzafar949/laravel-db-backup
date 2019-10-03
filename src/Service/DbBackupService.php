<?php


namespace Salman\DbBackup\Service;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DbBackupService
{
    private static $db = null;
    private static $db_user = null;
    private static $db_Pass = null;
    private static $disk = null;
    private static $folder = null;
    private static $process = null;
    private static $visibility = null;

    public function __construct()
    {
        self::$db = env('DB_DATABASE');
        self::$db_user = env('DB_USERNAME');
        self::$db_Pass = env('DB_PASSWORD');
        self::$disk = config('dbbackup.disk');
        self::$folder = config('dbbackup.folder_name');
        self::$visibility = config('dbbackup.visibility');
    }

    private static function StoreBackupOnS3($path)
    {
        $content = Storage::get($path);

        Storage::disk('s3')->put($path, $content);

        Storage::delete($path);

        return true;
    }

    public static function DoBackUp()
    {
        self::$process = new Process(self::RunBackupProcess());

        try
        {
            self::$process->mustRun();

            return "Backup Successful";
        }
        catch (ProcessFailedException $exception)
        {
            return "Backup Failed ". $exception;
        }
    }

    private static function RunBackupProcess()
    {
        $filename = self::$folder."/".self::GetBackupFileName();

        $exec  = sprintf(
            'mysqldump --compact --skip-comments -u%s -p%s %s > %s',
            self::$db_user,
            self::$db_Pass,
            self::$db,
            Storage::disk(self::$disk)->put($filename, fopen(storage_path($filename), 'r+', self::$visibility))
        );
//        self::$disk == 's3' ?? self::StoreBackupOnS3($filename);

        return $exec;
    }

    private static function GetBackupFileName()
    {
        $today = today()->format('Y-M-D');

        return (string) Str::uuid().'_'.$today.'sql';
    }
}
