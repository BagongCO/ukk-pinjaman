<?php
if (!function_exists('escapeString')) {
    function escapeString($string)
    {
        global $connect;
        return mysqli_real_escape_string($connect, $string);
    }
}
