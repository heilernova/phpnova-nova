<?php

/**
 * Convierte el testo del formato de snake case a camel case:
 * nombre_de_prueba = nombreDePrueba
 */
function nv_parse_snakecase_to_camelcase(string $text): string
{
    return preg_replace_callback(subject: $text, pattern: '/_\w/', callback: fn($val) => strtoupper(ltrim($val[0], '_')));
}