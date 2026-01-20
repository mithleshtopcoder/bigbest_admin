<?php


if (!function_exists('apiAuth')) {
    function apiAuth()
    {
        $apiAuth = request()->apiAuth ?? \App\Models\ApiUser::whereId(accessToken()->api_user_id)->first();
        request()->apiAuth = $apiAuth;
        return $apiAuth;
    }
}

if (!function_exists('accessToken')) {
    function accessToken()
    {
        $accessToken = request()->accessToken?? \App\Models\Token::where('token',request()->bearerToken())->first();
        request()->accessToken = $accessToken;
        return $accessToken;
    }
}

if (!function_exists('apiUserName')) {
    function apiUserName():string
    {
        $apiUser = apiAuth();
        return $apiUser->name ?? 'Undefined';
    }
}

if (!function_exists('abort_if_forbidden')) {
    function abort_if_forbidden(string $permission,$message = "You have not permission to this page (this is form custom halper)!"):void
    {
        abort_if(is_null(auth()->user()) || !auth()->user()->can($permission),403,$message);
    }
}

if (!function_exists('setUserTheme')) {
    function setUserTheme($theme)
    {
        $classes = [
            'default' => [
                'body' => '',
                'nav' => ' navbar-light ',
                'sidebar' => 'sidebar-dark-primary ',
            ],
            'light' => [
                'body' => '',
                'nav' => ' navbar-white ',
                'sidebar' => ' sidebar-light-lightblue '
            ],
            'dark' => [
                'body' => ' dark-mode ',
                'nav' => ' navbar-dark ',
                'sidebar' => ' sidebar-dark-secondary '
            ]
        ];
        return $classes[$theme] ?? [
                'body' => '',
                'nav' => ' navbar-light ',
                'sidebar' => 'sidebar-dark-primary ',
            ];
    }
}

if (!function_exists('price_format')) {
    function price_format($price)
    {
        return number_format($price, 2, ".", " ");
    }
}
if (!function_exists('nf')) {
    function nf($number)
    {
        return number_format($number, 0, "", " ");
    }
}

if (!function_exists('convert_text_latin')) {
    function convert_text_latin($text)
    {
        $cyr = [
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
            'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',
            'Р', 'С', 'Т', 'У', 'Ў', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'
        ];
        $lat = [
            'a', 'b', 'v', 'g', 'd', 'e', 'yo', 'j', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
            'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sh', 'a', 'i', 'y', 'e', 'yu', 'ya',
            'A', 'B', 'V', 'G', 'D', 'E', 'Yo', 'J', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P',
            'R', 'S', 'T', 'U', 'O', 'F', 'H', 'Ts', 'Ch', 'Sh', 'Sh', 'A', 'I', 'Y', 'e', 'Yu', 'Ya'
        ];
        $textlat = mb_strtoupper(removeChars(str_replace($cyr, $lat, $text)));
        return $textlat;
    }
}

if (!function_exists('removeChars')) {
    function removeChars($value)
    {
        $title = str_replace(array('\'', '"', ',', ';', '.', '’','-','‘','/'), ' ', $value);
        return $title;
    }
}

if (!function_exists('removeMarks')) {
    function removeMarks($value)
    {
        $title = str_replace(array('\'', '’','‘','`','?'), '', $value);
        return $title;
    }
}

if (!function_exists('phoneFormat')) {
    function phoneFormat($value)
    {
        if (strlen($value) == 9)
            return '+998'.$value;
        else
            return $value;
    }
}
if (!function_exists('message_set'))
{
    function message_set($message,$type,$timer = 15)
    {
        session()->put('_message',$message);
        session()->put('_type',$type);
        session()->put('_timer',$timer*1000);
    }
}

if (!function_exists('error_message'))
{
    function error_message($message)
    {
        message_set($message,'error',5);
    }
}

if (!function_exists('success_message'))
{
    function success_message($message)
    {
        message_set($message,'success',5);
    }
}

if (!function_exists('warning_message'))
{
    function warning_message($message)
    {
        message_set($message,'warning',5);
    }
}

if (!function_exists('info_message'))
{
    function info_message($message)
    {
        message_set($message,'info',5);
    }
}

if (!function_exists('message_clear'))
{
    function message_clear()
    {
        session()->pull('_message');
        session()->pull('_type');
        session()->pull('_timer');
    }
}

if (!function_exists('sendByTelegram'))
{
    function sendByTelegram($message,$chatID,$token)
    {
        $url = "https://api.telegram.org/bot" . $token . "/sendMessage?parse_mode=HTML&chat_id=" . $chatID;
        $url = $url . "&text=" . urlencode($message);

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-type:application/json']);

        //ssl settings
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_close($ch);

        return true;
    }
}

if (!function_exists('logObj'))
{
    function logObj($object)
    {
        $unset_list = [
            'updated_at',
            'created_at',
            'email_verified_at',
            'roles'
        ];

        foreach ($unset_list as $item) {
            unset($object->{$item});
            unset($object[$item]);
        }

        return json_encode($object);
    }
}

if (!function_exists('generateUniqueCode')) {
    function generateUniqueCode($firstName)
    {
        $fn = isset($firstName)?$firstName:'YC';
        return ($fn.'-'.Str::random(10));
    }
}

if (!function_exists('getLastName')) {
    function getLastName($Full_Name)
    {
        $wordsArray = explode(" ", $Full_Name);
        array_shift($wordsArray);
        $last_name = implode(" ", $wordsArray);
        if ($last_name) {
            return $last_name;
        } else {
            return ' ';
        }

    }
}
if (!function_exists('getFirstName')) {
    function getFirstName($Full_Name)
    {
        $nameArray = explode(" ", $Full_Name);
        $firstName = $nameArray[0];
        $firstName = $nameArray[0];
        if ($firstName) {
            return $firstName;
        } else {
            return ' ';
        }
    }
}
if (!function_exists('generatePassword')) {
    function generatePassword($length)
    {
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $specialChars = '!@#$%^&*()_-+=<>?';
        $allChars = $lowercase . $uppercase . $numbers . $specialChars;
        $password = Str::random($length, $allChars);
        if ($password) {
            return $password;
        } else {
            return 'rstopcoder-customer';
        }
    }
}

if (!function_exists('getNumberInWord')) {
    function getNumberInWord($number)
    {
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'one', '2' => 'two',
        '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
        '7' => 'seven', '8' => 'eight', '9' => 'nine',
        '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
        '13' => 'thirteen', '14' => 'fourteen',
        '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
        '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
        '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
        '60' => 'sixty', '70' => 'seventy',
        '80' => 'eighty', '90' => 'ninety');
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                " " . $digits[$counter] . $plural . " " . $hundred
                :
                $words[floor($number / 10) * 10]
                . " " . $words[$number % 10] . " "
                . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
        "." . $words[$point / 10] . " " .
        $words[$point = $point % 10] : '';

        return $result . "Rupees  " . $points . " Paise";
    }
}



if (!function_exists('numberToAlphabet')){
    function numberToAlphabet($number)
    {
        $number = max(1, (int)$number);
        $numberString = (string)$number;
        return strlen($numberString);
        $result = '';
        for ($i = 0; $i < strlen($numberString); $i++) {
            $digit = (int)$numberString[$i];
            $letter = chr(ord('A') + $digit - 1);
            $result .= $letter;
        }
        return $result;
    }
}
if (!function_exists('convertToMultiDigitsNumber')){
    function convertToMultiDigitsNumber($number, $digits = 1)
    {
        // 1 = > 001
        if ($number < 1 || $digits <1) {
            return 0;
        }
        $number = max(0, (int)$number);
        $zerosNeeded = str_pad($number, $digits, "0", STR_PAD_LEFT);
        return $zerosNeeded;
    }
}

if (!function_exists('convertToMultiDigitsCharacter')){
    function convertToMultiDigitsCharacter($number, $numAlphabets = 1)
    {
        // 1, 1  => A
        // 1, 2  => AA
    if ($number < 1 || $numAlphabets < 1) {
        return 'A';
    }
    $result = '';
    while ($number > 0) {
        $remainder = ($number - 1) % 26;
        $result = chr(ord('a') + $remainder) . $result;
        $number = floor(($number - 1) / 26);
    }
    $result = str_repeat($result, $numAlphabets);
    return strtoupper($result);
    }
}


if (!function_exists('convertToMultiDigits')){
    function convertToMultiDigits($type,$number, $numAlphabets = 1)
    {
        if ($type == 1) {
            // 1 = > 001
            if ($number < 1 || $numAlphabets <1) {
                return 0;
            }
            $number = max(0, (int)$number);
            $zerosNeeded = str_pad($number, $numAlphabets, "0", STR_PAD_LEFT);
            return $zerosNeeded;
        } else {
            // 1, 1  => A
            // 1, 2  => AA
            if ($number < 1 || $numAlphabets < 1) {
                return 'A';
            }
            $result = '';
            while ($number > 0) {
                $remainder = ($number - 1) % 26;
                $result = chr(ord('a') + $remainder) . $result;
                $number = floor(($number - 1) / 26);
            }
            $result = str_repeat($result, $numAlphabets);
            return strtoupper($result);
        }

    }
}








if (!function_exists('ticketstatus')){
    function ticketstatus()
    {
        return [
            '1'=> 'open',
            '2'=> 'closed',
            '3'=> 'In-progress',
          ];
    }
}

if (!function_exists('ticketpriority')){
    function ticketpriority()
    {
        return [
            '1' => 'High',
            '2' => 'Medium',
            '3' => 'Low',
        ];
    }
}

if (!function_exists('ticketcategory')){
    function ticketcategory()
    {
        return [
            '1' => 'Bug',
            '2' => 'Feature',
            '3' => 'Request',
            '4' => 'Support',

        ];
    }
}
if (!function_exists('getticketcategory')){
    function getticketcategory($id)
    {
        if ($id == '1') {
            return 'Bug';
        } elseif ($id == '2') {
            return 'Feature';
        } elseif ($id == '3') {
            return 'Request';
        } elseif ($id == '4') {
            return 'Support';
        } else {
            return ' ';
        }
    }
}

if (!function_exists('helpTopic')){
    function helpTopic()
    {
        return [
            '1' => 'Project',
            '2' => 'Item Purchase',
            '3' => 'Service',
        ];
    }
}

if (!function_exists('slugify')){
    function slugify($string)
    {
        // Convert the string to lowercase
        $string = strtolower($string);
        // Replace spaces and multiple consecutive spaces with a single hyphen
        $string = preg_replace('/\s+/', '-', $string);
        // Remove all characters that are not alphanumeric, hyphens, or underscores
        $string = preg_replace('/[^a-z0-9-_]/', '', $string);
        return $string;
    }
}





















