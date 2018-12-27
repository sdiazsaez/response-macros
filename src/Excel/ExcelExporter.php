<?php

namespace Larangular\ResponseMacros\Excel;

use Cyberduck\LaravelExcel\Exporter\Excel;
use Cyberduck\LaravelExcel\Serialiser\BasicSerialiser;
use Illuminate\Support\Collection;
use Cyberduck\LaravelExcel\ExporterFacade;

class ExcelExporter {

    public function load(array $array): Excel {
        $collection = $this->makeFlatCollection($array);
        return ExporterFacade::make('Excel')
                               ->load($collection)
                               ->setSerialiser($this->getHeaderSerializer($collection));
    }

    private function makeFlatCollection(array $array): Collection {
        foreach($array as $key => $value) {
            $array[$key] = array_dot($value);
        }

        return Collection::make($array);
    }

    private function getHeaderSerializer(Collection $collection): BasicSerialiser {
        $lastCount = 0;
        $higherCountIndex = 0;

        foreach($collection as $key => $value) {
            $valueLenght = count($value);
            if($lastCount < $valueLenght) {
                $lastCount = $valueLenght;
                $higherCountIndex = $key;
            }
        }

        return new HeaderSerializer(array_keys($collection[$higherCountIndex]));
    }
}
