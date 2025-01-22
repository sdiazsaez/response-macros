<?php

namespace Larangular\ResponseMacros\Macros;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Larangular\ResponseMacros\ResponseMacroInterface;

class CSV implements ResponseMacroInterface {

    public function run($factory): void {
        $factory->macro('csv', function (array $vars, $status = 200, array $header = []) {
            if (count($vars) <= 0) {
                return Response::make('The request contains no records', 412);
            }


            function arrayToCsv(array $data): string {
                // Open output buffer
                $output = fopen('php://temp', 'r+');

                // Extract and write headers if array is associative or contains arrays
                if (isset($data[0]) && is_array($data[0])) {
                    fputcsv($output, array_keys($data[0]));
                }

                // Write rows to CSV
                foreach ($data as $row) {
                    if (is_array($row)) {
                        fputcsv($output, $row);
                    } else {
                        fputcsv($output, [$row]); // Handle non-associative single values
                    }
                }

                // Get content and close output buffer
                rewind($output);
                $csvContent = stream_get_contents($output);
                fclose($output);

                return $csvContent;
            }

            /**
             * Match last resource name
             * /route/resource/method.csv => method
             */
            $re = '/\/([^\/]+)(\.|$)/';
            $str = Request::path();
            preg_match($re, $str, $matches);

            $fileName = $matches[1] ?? 'untitled';
            $fileName .= '.csv';

            if (Arr::has($vars, ['current_page', 'data'])) {
                $vars = $vars['data'];
            }

            // Convert array to CSV string
            $csvContent = arrayToCsv($vars);

            if (empty($header)) {
                $header['Content-Type'] = 'text/csv';
                $header['Content-Disposition'] = "attachment; filename=\"$fileName\"";
            }

            return Response::make($csvContent, $status, $header);
        });
    }
}
