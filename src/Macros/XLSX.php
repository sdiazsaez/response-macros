<?php

namespace Larangular\ResponseMacros\Macros;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Larangular\ResponseMacros\Excel\ExcelExporter;
use Larangular\ResponseMacros\ExcelSerializers\HeaderSerializer;
use Larangular\ResponseMacros\ResponseMacroInterface;

class XLSX implements ResponseMacroInterface {

    public function run($factory): void {
        $factory->macro('xlsx', static function (array $vars, $status = 200, array $header = [], $xml = null) {

            if (count($vars) <= 0) {
                return Response::make('La solicitud no contiene registros', 412);
            }

            /**
             * Match last resource name
             * /route/resource/method.xlsx => method
             */
            $re = '/\/([^\/]+)(\.|$)/';
            $str = Request::path();
            preg_match($re, $str, $matches);

            $fileName = $matches[1] ?? 'untitled';
            $fileName .= '.xls';

            $excel = (new ExcelExporter())->load($vars);
            if (empty($header)) {
                $header['Content-Type'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            }

            return Response::make($excel->stream($fileName), $status, $header);
        });
    }

}
