<?php

if (!function_exists('myHelper')) {
    /**
     * Get the MyHelper instance
     *
     * @return \App\Helpers\MyHelper
     */
    function myHelper()
    {
        return new \App\Helpers\MyHelper();
    }
}

if (!function_exists('image_url')) {
    function image_url($image, $folder = 'common')
    {
        return \App\Helpers\MyHelper::getImage($image, $folder);
    }
}