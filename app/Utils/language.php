<?php

if (! function_exists('translate')) {
    function translate($key)
    {
        try {
            $local = app()->getLocale();
            $lang_array = include base_path('resources/lang/'.$local.'/lang.php');
            $processed_key = ucfirst(str_replace('_', ' ', str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', $key)));
            if (! array_key_exists($key, $lang_array)) {
                $lang_array[$key] = $processed_key;
                $str = '<?php return '.var_export($lang_array, true).';';
                file_put_contents(base_path('resources/lang/'.$local.'/lang.php'), $str);
                $result = $processed_key;
            } else {
                $result = __('lang.'.$key);
            }

            return $result;
        } catch (\Exception $exception) {
            return $key;
        }
    }
}

if (! function_exists('autoTranslator')) {
    function autoTranslator($q, $sl, $tl): array|string
    {
        $res = file_get_contents('https://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl='.$sl.'&tl='.$tl.'&hl=hl&q='.urlencode($q), $_SERVER['DOCUMENT_ROOT'].'/transes.html');
        $res = json_decode($res);

        return str_replace('_', ' ', $res[0][0][0]);
    }
}
