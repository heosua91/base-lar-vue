<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class Helper
{
    public static function csvToArray($header_csv, $filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = [];
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                if (!$header && array(null) === $row) {
                    // if blank header
                    throw new Exception(trans('common.import.no_header'), 696);
                }
                // Check blank line
                if (array(null) !== $row) {
                    $new_data = array();
                    foreach ($row as $value) {
                        $new_data[] = trim(mb_convert_encoding($value, "UTF-8", "cp932"));
                    }
                    if (!$header) {
                        if ($new_data !== $header_csv)
                            throw new Exception(trans('common.import.header_not_match'), 696);
                        else
                            $header = $new_data;
                    } else {
                        try {
                            $data[] = array_combine($header, $new_data);
                        } catch (Exception $e) {
                            Log::error('Import error - name: ' . $filename . ' - row: ' . (count($data) + 2));
                            continue;
                        }
                        if (count($data) > config('common.max_row_import_csv')) {
                            throw new Exception(trans('common.import.max_row'), 696);
                        }
                    }
                } else {
                    Log::error('Import error - name: ' . $filename . ' - row: ' . (count($data) + 2));
                    continue;
                }
            }
            fclose($handle);
        }
        return $data;
    }

    public static function getListDayOfMonth($date)
    {
        $start = Carbon::parse($date)->startOfMonth();
        $end = Carbon::parse($date)->endOfMonth();

        $dates = [];
        while ($start->lte($end)) {
            $dates[] = $start->copy();
            $start->addDay();
        }

        return $dates;
    }

    public static function checkSpecialCharacter($valueSearch)
    {
        $valueSearch = strpos($valueSearch, '%') !== false ? preg_replace('/\%/', '\%', $valueSearch) : $valueSearch;
        $valueSearch = strpos($valueSearch, '_') !== false ? preg_replace('/\_/', '\_', $valueSearch) : $valueSearch;
        $valueSearch = preg_match('/\\\/', $valueSearch) !== false ? addslashes($valueSearch) : $valueSearch;

        return $valueSearch;
    }

    public static function checkImageBase64($image)
    {
        return (substr($image, 0, 11) == 'data:image/');
    }

    public static function makePagination($queryCount, $queryPaginate, $itemPerPage, $page)
    {
        /* Get total */
        $totalCount = $queryCount->count();

        /* Get data */
        $slice = $page ? $itemPerPage * ($page - 1) : 0;
        $data = $queryPaginate->slice($slice, $itemPerPage)->toArray();

        return new \Illuminate\Pagination\LengthAwarePaginator(array_values($data), $totalCount, $itemPerPage);
    }

    public static function getFileNameExportCsv(string $name)
    {
        return $name . '_' . strtotime(Carbon::now()) . '.csv';
    }

    public static function sortAscByValueArray($array, $field)
    {
        usort($array, function ($a, $b) use ($field) {
            return strnatcmp($a[$field], $b[$field]);
        });

        return $array;
    }

    public static function subStr($string, $length = 15)
    {
        return mb_strlen($string) >= $length ? (mb_substr($string, 0, $length) . '...') : $string;
    }
}