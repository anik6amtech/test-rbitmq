<?php

if (! function_exists('responseFormatter')) {
    function responseFormatter($constant, $content = null, $errors = []): array
    {
        $constant = [
            'response_code' => $constant['response_code'],
            'message' => translate($constant['message']),
        ];
        $constant['content'] = $content;
        $constant['errors'] = $errors;

        return $constant;
    }
}

