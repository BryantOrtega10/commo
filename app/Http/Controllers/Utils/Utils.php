<?php

namespace App\Http\Controllers\Utils;

use App\Models\Utils\LogsModel;
use DateTime;
use Illuminate\Support\Facades\Auth;

class Utils
{
    public static function rowFormat($index, $value)
    {
        $endWord = explode("_", $index);
        $endWord = last($endWord);
        if ($endWord == "date" && !empty($value)) {
            $date = DateTime::createFromFormat('Y-m-d', '1899-12-30');
            $date->modify("+$value days");
            return $date->format('m/d/Y');
        }
        return $value;
    }

    public static function dbFormat($index, $value)
    {
        $endWord = explode("_", $index);
        $endWord = last($endWord);
        if ($endWord == "date" && !empty($value)) {
            $date = DateTime::createFromFormat('Y-m-d', '1899-12-30');
            $date->modify("+$value days");
            return $date->format('Y-m-d');
        }
        return $value;
    }

    public static function createLog($description, $module, $action, $user = null)
    {
        $entry_user = Auth::user();
        if(!isset($entry_user)){
            $entry_user = $user;
        }
        $ip = request()->ip();
        LogsModel::create([
            'description' => $description,
            'ip_address' => $ip,
            'module' => $module,
            'action' => $action,
            'fk_entry_user' => $entry_user->id
        ]);
    }
}
