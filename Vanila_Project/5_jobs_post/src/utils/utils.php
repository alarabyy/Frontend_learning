<?php

namespace utils;

use UnexpectedValueException;

class Utils
{
    private static $basePath = null;


    // ---------------------------------------------------------------
    // to use now basepath
    public static function base_path($path)
    {
        if(self::$basePath == null) {
            self::$basePath = $GLOBALS['config']['baseFolder'] !== '' 
                                    ? $_SERVER['DOCUMENT_ROOT'] . '/' . $GLOBALS['config']['baseFolder'] . "/"
                                    : $_SERVER['DOCUMENT_ROOT'];
        }
        return self::$basePath . $path;
    }
    // ---------------------------------------------------------------
// use to desroy code http only
    public static function abort($code = 404)
    {
        http_response_code($code);
        echo "Status Code: {$code}";
    }
    // ---------------------------------------------------------------

    public static function generateUUID()
{
    $data = openssl_random_pseudo_bytes(16); // توليد 16 بايت عشوائي باستخدام openssl
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // تعديل البايت السادس ليتوافق مع إصدار UUID
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // تعديل البايت الثامن ليتوافق مع إصدار UUID
    $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4)); // تنسيق البيانات كـ UUID

    return $uuid; // إرجاع UUID المولد
}
    // ---------------------------------------------------------------

    public static function validateProperty(string $propName, string $propValue, int $maxLength, int $minLength): void
    {
        if (!(!empty($propValue) && strlen($propValue) <= $maxLength && strlen($propValue) >= $minLength)) {
            throw new UnexpectedValueException("$propName cannot be null and more than $maxLength char or less than $minLength");
        }
    }


}
