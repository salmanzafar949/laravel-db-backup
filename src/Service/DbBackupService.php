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

    public static function KickStartBackup()
    {
        self::$db = env('DB_DATABASE');
        self::$db_user = env('DB_USERNAME');
        self::$db_Pass = env('DB_PASSWORD');
        self::$disk = config('dbbackup.disk');
        self::$folder = config('dbbackup.folder');
        self::$visibility = config('dbbackup.visibility');
        $output  = self::DoBackUp();
        return $output;
    }

    private static function StoreBackupOnS3($path)
    {
        $content = Storage::get($path);

        Storage::disk('s3')->put($path, $content);

        Storage::delete($path);

        return true;
    }

    private static function DoBackUp()
    {
        $command = self::RunBackupProcess();

        self::$process = new Process($command);

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

        $path = self::GetBackupFileNameAndPath();

        $exec  = sprintf(
            'mysqldump --compact -u%s -p%s %s > %s',
            self::$db_user,
            self::$db_Pass,
            self::$db,
            storage_path($path)
        );

        self::$disk == 's3' ?? self::StoreBackupOnS3($filename);

        return $exec;
    }

    private static function GetBackupFileNameAndPath()
    {
        if (!is_dir(storage_path(self::$folder)))
            mkdir(storage_path(self::$folder), 0777);

        $today = today()->format('Y-M-D');
        $name = (string) Str::uuid().'_'.$today.'.sql';
        $path = self::$folder.'/'.$name;

        return $path;
    }
}
