<?php
namespace Phpnova\Nova\Database;

use PDOStatement;

/**
 * Parce the values
 */
function db_parce_result(array $values, PDOStatement $stmt, array $config)
{
    $params = [];
    foreach ($values as $key => $val) {
        $column_meta = $stmt->getColumnMeta($key);
        $native_type = $column_meta['native_type'];

        foreach ($values as $index => $value) {
            $column_meta = $stmt->getColumnMeta($index);
            $native_type = $column_meta['native_type'];

            if ($config['driver_name'] == 'mysql') {
                if ($native_type == 'NEWDECIMAL'){
                    $value = (float)$value;
                } else if ($native_type == 'BLOB' || $native_type == 'VAR_STRING'){
                    if (is_string($value)){
                        if (nv_is_json_structure($value)){
                            $json = json_decode($value);
                            if (json_last_error() == JSON_ERROR_NONE){
                                $value = $json;
                            }
                        }
                    }
                }
            }

            if ($config['driver_name'] == 'pgsql') {
                if ($native_type == 'numeric'){
                    $value = (float)$value;
                }

                if ($native_type == 'json'){
                    $value = json_decode($value);
                }
            }

            $name_field = $column_meta['name'];
            $writing_style = $config['result_parce_writing_style'] ?? null;

            if ($writing_style == 'camelcase') $name_field = nv_parse_snakecase_to_camelcase($name_field);
            if ($writing_style == 'snakecase') $name_field = nv_parse_camelcase_to_snakecase($name_field);

            // if ($writing_style == 'snakecase')

            $params[$name_field] = $value;
        }

    }
    return (object)$params;
}