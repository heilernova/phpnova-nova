<?php

function nv_is_json_structure(string $json_string): bool
{
    return preg_match('/^\{?.+\}/', $json_string) > 0 || preg_match('/^\[?.+\]/', $json_string) > 0;
}