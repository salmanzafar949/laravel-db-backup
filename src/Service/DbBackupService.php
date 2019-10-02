<?php


namespace Salman\DbBackup\Service;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DbBackupService
{
    protected static $db = null;
    protected static $db_user = null;
    protected static $db_Pass = null;
    protected static $disk = null;
    protected static $folder = null;
    protected static $process = null;

    public function __construct()
    {
        self::$db = env('DB_DATABASE');
        self::$db_user = env('DB_USERNAME');
        self::$db_Pass = env('DB_PASSWORD');
        self::$disk = config('dbbackup.disk');
        self::$folder = config('dbbackup.folder_name');
    }

    /*public static function StoreBackup()
    {
        switch (self::$disk)
        {
            case 's3':
                self::DoBackUp('s3');
                break;
            case 'local':
                self::DoBackUp('local');
                break;
            default:
                return null;
        }

    }*/

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

    protected static function RunBackupProcess()
    {
        $exec  = sprintf(
            'mysqldump --compact --skip-comments -u%s -p%s %s > %s',
            self::$db_user,
            self::$db_Pass,
            self::$db,
            storage_path(self::$folder."/".self::GetBackupFileName())
        );

        return $exec;
    }

    protected static function GetBackupFileName()
    {
        $today = today()->format('Y-M-D');

        return (string) Str::uuid().'_'.$today.'sql';
    }
}
