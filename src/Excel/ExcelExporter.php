<?php

namespace Larangular\ResponseMacros\Excel;

use Cyberduck\LaravelExcel\Exporter\Excel;
use Cyberduck\LaravelExcel\ExporterFacade;
use Cyberduck\LaravelExcel\Serialiser\BasicSerialiser;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ExcelExporter {

    public function load(array $array): Excel {
        $collection = $this->makeFlatCollection($array);
        return ExporterFacade::make('Excel')
                             ->load($collection)
                             ->setSerialiser($this->getHeaderSerializer($collection));
    }

    protected function makeFlatCollection(array $array): Collection {
        if ($this->isAssoc($array)) {
            $array = [$array];
        }

        foreach ($array as $key => $value) {
            $array[$key] = Arr::dot($value);
        }

        return Collection::make($array);
    }

    protected function getHeaderSerializer(Collection $collection): BasicSerialiser {
        $lastCount = 0;
        $higherCountIndex = 0;

        foreach ($collection as $key => $value) {
            $valueLenght = count($value);
            if ($lastCount < $valueLenght) {
                $lastCount = $valueLenght;
                $higherCountIndex = $key;
            }
        }

        return new HeaderSerializer(array_keys($collection[$higherCountIndex]));
    }

    private function isAssoc(array $arr): bool {
        if ([] === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
