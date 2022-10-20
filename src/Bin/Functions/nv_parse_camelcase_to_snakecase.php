<?php

/**
 * Convierte un testo de camel case a lower case
 */
function nv_parse_camelcase_to_snakecase(string $text): string
{
    return preg_replace_callback(subject: $text, pattern: '/[A-Z]/', callback: fn($val) => "_" . strtolower($val[0]));
}