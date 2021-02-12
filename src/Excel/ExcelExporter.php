<?php

namespace Larangular\ResponseMacros\Excel;

use Cyberduck\LaravelExcel\Exporter\Excel;
use Cyberduck\LaravelExcel\ExporterFacade;
use Cyberduck\LaravelExcel\Serialiser\BasicSerialiser;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ExcelExporter {

    public function load(array $array): Excel {
        $config = $this->getConfig($array);
        return ExporterFacade::make('Excel')
                             ->load($config['data'])
                             ->setSerialiser($config['serializer']);
    }

    private function getConfig(array $array): array {
        if ($this->isAssoc($array)) {
            $array = [$array];
        }

        $header = [];
        foreach ($array as $key => $value) {
            $flatArray = Arr::dot($value);
            $header = array_merge($header, array_keys($flatArray));
            $isArrayValue = array_filter($flatArray, static function($value) {
                return is_array($value);
            });

            $isArrayValue = array_fill_keys(array_keys($isArrayValue), '');
            $array[$key] = array_merge($flatArray, $isArrayValue);
        }

        $header = array_values(array_unique($header));
        return [
            'data' => Collection::make($array),
            'serializer' => new HeaderSerializer($header)
        ];
    }

    private function isAssoc(array $arr): bool {
        if ([] === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
