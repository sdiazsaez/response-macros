<?php

namespace Larangular\ResponseMacros\Macros;

use Illuminate\Support\Collection;
use Larangular\ResponseMacros\ResponseMacroInterface;
use Cyberduck\LaravelExcel\ExporterFacade;
use Larangular\ResponseMacros\ExcelSerializers\HeaderSerializer;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Larangular\ResponseMacros\Excel\ExcelExporter;

class XLSX implements ResponseMacroInterface {

    public function run($factory) {
        $factory->macro('xlsx', function (array $vars, $status = 200, array $header = [], $xml = null) {

            if(count($vars) <= 0) {
                return Response::make('La solicitud no contiene registros', 412);
            }

            $re = '/\/([^\/]+)\./';
            $str = Request::path();
            preg_match($re, $str, $matches);
            $fileName = $matches[1] .'.xlsx';

            $excel = (new ExcelExporter())->load($vars);
            if (empty($header)) {
                $header['Content-Type'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            }

            return Response::make($excel->stream($fileName), $status, $header);
        });
    }

}
