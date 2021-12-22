<?php

use App\Enums\LocationStatus;
use App\Libraries\Odl\OdlFetcher;
use App\Models\Location;
use h4kuna\Number\NumberFormatState;

const DATE = 'date';
const DATETIME = 'datetime';

if (!function_exists('l')) {
    function l($type)
    {
        return __('common.date_formats.' . $type);
    }
}

if (!function_exists('getYearsFrom')) {
    function getYearsFrom($year)
    {
        return $year < date('Y') ? $year . '-' . date('Y') : date('Y');
    }
}

if (!function_exists('formatBoolean')) {
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

if (!function_exists('getOdlStatusEnumFromId')) {
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

if (!function_exists('getKeyValuePairsFromStr')) {
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

if (!function_exists('formatStatus')) {
    function formatStatus(LocationStatus $status)
    {
        return __('common.statuses.' . $status->value);
    }
}

if (!function_exists('removeNulls')) {
    function removeNulls(array $arr)
    {
        return array_filter($arr, function ($item) {
            return $item != null;
        });
    }
}

if (!function_exists('itemIf')) {
    function itemIf($item, $isVisible, $default = null)
    {
        return $isVisible ? $item : $default;
    }
}

if (!function_exists('getStaticMapUrlForLocation')) {
    function getStaticMapUrlForLocation(Location $location): string
    {
        // https://yandex.com/dev/maps/staticapi/doc/1.x/dg/concepts/localization-docpage/
        $baseUrl = 'https://static-maps.yandex.ru/1.x/?lang=en_RU&spn=0.01,0.01&l=map';
        $coordinatesStr = $location->longitude . ',' . $location->latitude;

        return "{$baseUrl}&ll={$coordinatesStr}&pt={$coordinatesStr},pm2ntl";
    }
}

if (!function_exists('getGoogleMapsUrlForLocation')) {
    function getGoogleMapsUrlForLocation(Location $location): string
    {
        $baseUrl = 'https://www.google.com/maps/search/?api=1&query=';
        $coordinatesStr = $location->latitude . ',' . $location->longitude;

        return $baseUrl . $coordinatesStr;
    }
}

if (!function_exists('formatDecimal')) {
    function formatDecimal($value, int $decimals = 2): string
    {
        $numberFormat = new NumberFormatState(2);

        return $numberFormat->format($value);
    }
}

if (!function_exists('getOdlFetcher')) {
    function getOdlFetcher(): OdlFetcher
    {
        return new OdlFetcher(config('odl.base_url'), config('odl.user'), config('odl.password'));
    }
}

if (!function_exists('getHeroiconSvgImageHtml')) {
    function getHeroiconSvgImageHtml(string $iconName): string
    {
        return '<img src="' . asset("vendor/blade-heroicons/{$iconName}.svg") . '"/>';
    }
}
