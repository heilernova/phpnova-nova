<?php
function nv_generate_token(int $long = 8): string
{
    if ($long < 4) $long = 4;
    return bin2hex(random_bytes(($long - ($long % 2)) / 2));
}