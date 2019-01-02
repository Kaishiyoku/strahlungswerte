<?php

const DATE = 'date';
const DATETIME = 'datetime';

if (! function_exists('l')) {
    function l($type)
    {
        return __('common.date_formats.' . $type);
    }
}

if (! function_exists('getYearsFrom')) {
    function getYearsFrom($year)
    {
        return $year < date('Y') ? $year . '-' . date('Y') : date('Y');
    }
}

if (! function_exists('formatBoolean')) {
    function formatBoolean($bool)
    {
        if ($bool == true || $bool == 1) {
            $str = __('common.lists.boolean.1');
        } else {
            $str = __('common.lists.boolean.0');
        }

        return $str;
    }
}

if (! function_exists('getIdFromSlug')) {
    function getIdFromSlug($slug)
    {
        if (!strpos($slug, '-')) {
            return null;
        }

        $values = explode('-', $slug);

        if (empty($values[0] || empty($values[1])) || $values[0] == '' || $values[1] == '') {
            return null;
        }

        return $values[0];
    }
}

if (! function_exists('toSlug')) {
    function toSlug($id, $title)
    {
        return $id . '-' . \Illuminate\Support\Str::slug($title);
    }
}

if (! function_exists('getOdlStatusEnumFromId')) {
    function getOdlStatusEnumFromId($id)
    {
        $statusMappings = [
            0 => 'FAULTY',
            1 => 'OPERATIONAL',
            128 => 'TEST_MODE',
            2048 => 'MAINTENANCE',
        ];

        return $statusMappings[$id];
    }
}

if (! function_exists('getKeyValuePairsFromStr')) {
    function getKeyValuePairsFromStr($str)
    {
        if (empty($str)) {
            return collect();
        }

        $list = collect(array_map(function ($value) {
            return explode(',', $value);
        }, explode(';', $str)));

        $list = $list->mapWithKeys(function ($item) {
            return [$item[0] => $item[1]];
        });

        return $list;
    }
}

if (! function_exists('formatStatus')) {
    function formatStatus(\App\Models\Status $status)
    {
        return __('common.statuses.' . $status->name);
    }
}

if (! function_exists('removeNulls')) {
    function removeNulls(array $arr) {
        return array_filter($arr, function ($item) {
            return $item != null;
        });
    }
}

if (! function_exists('itemIf')) {
    function itemIf($item, $isVisible, $default = null) {
        return $isVisible ? $item : $default;
    }
}