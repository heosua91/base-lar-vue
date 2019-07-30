<?php

namespace App\Helpers;

use Carbon\Carbon;
use Log;
use AWS;
use Exception;
use Aws\Sns\Exception\SnsException;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

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

    public static function pushNotificationFirebase($body, $href, $icon, $tokens)
    {
        if ($tokens == null || !is_array($tokens) || !count($tokens)) {
            return;
        }
        Log::debug('pushNotificationFirebase: [body] = ' . $body . ', [href] = ' . $href . ', [icon] = ' . $icon . ', [numToken] = ' . count($tokens));
        $header = [
            'Authorization' => 'key=' . config('aws.firebase_private_key'),
            'Content-Type' => 'application/json',
        ];
        $message = [
            'href' => $href,
            'body' => $body,
            'title' => config('aws.push_notification_title'),
            'icon' => $icon,
            'requireInteraction' => true,
        ];
        $payload = [
            'registration_ids' => $tokens,
            'data' => $message
        ];

        try {
            $client = new Client();
            $response = $client->post(config('aws.firebase_api_url'), [
                RequestOptions::HEADERS => $header,
                RequestOptions::JSON => $payload,
            ]);

            Log::debug($response->getBody());
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    public static function pushNotification(array $message, string $key, any $stringValue)
    {
        try {
            $sns = AWS::createClient('sns');
            $sns->publish([
                'Message' => json_encode([
                    'default' => json_encode($message),
                    'APNS_SANDBOX' => json_encode([
                        'aps' => [
                            'alert' => $message,
                            'sound' => 'default',
                        ]
                    ])
                ]),
                'MessageStructure' => 'json',
                'TopicArn' => config('push-notification.topic_arn'),
                'MessageAttributes' => [
                    $key => [
                        'DataType' => 'Number',
                        'StringValue' => $stringValue,
                    ]
                ]
            ]);
        } catch (SnsException $e) {
            Log::error($e);

            return false;
        }

        return true;
    }

    public static function createEndpoint(string $device_token = null, int $typePlatform)
    {
        Log::debug('createEndpoint ' . '$device_token = ' . $device_token . ';$typePlatform = ' . $typePlatform);
        if (!$device_token) {
            return '';
        }
        try {
            $sns = AWS::createClient('sns');
            if ($typePlatform == config('push-notification.device_platform.android')) {
                $platformArn = config('push-notification.android_application_arn');
            } else {
                $platformArn = config('push-notification.ios_application_arn');
            }
            $result = $sns->createPlatformEndpoint(array(
                'PlatformApplicationArn' => $platformArn,
                'Token' => $device_token,
            ));
            if ($result['EndpointArn']) {
                $sns->setEndpointAttributes(array(
                    'EndpointArn' => $result['EndpointArn'],
                    'Attributes' => array(
                        'Enabled' => 'true',
                    ),
                ));
            }

            return $result['EndpointArn'] ?? '';
        } catch (SnsException $e) {
            Log::error($e);
            return '';
        }
    }

    public static function subscribeToTopic(string $endpoint_arn = null, string $key, any $stringValue)
    {
        Log::debug('subscribeToTopic ' . '$endpoint_arn = ' . $endpoint_arn . '; $key = ' . $key . '; $stringValue = ' . $stringValue);
        if (!$endpoint_arn) {
            return '';
        }
        try {
            $sns = AWS::createClient('sns');
            $result = $sns->subscribe([
                'Endpoint' => $endpoint_arn,
                'Protocol' => 'application',
                'TopicArn' => config('push-notification.topic_arn'),
            ]);
            if ($result['SubscriptionArn']) {
                $sns->setSubscriptionAttributes(array(
                    'SubscriptionArn' => $result['SubscriptionArn'],
                    'AttributeName' => 'FilterPolicy',
                    'AttributeValue' => json_encode([
                        $key => [[
                            'numeric' => ['=', $stringValue]
                        ]]
                    ])
                ));
            }

            return $result['SubscriptionArn'] ?? '';
        } catch (SnsException $e) {
            Log::error($e);

            return '';
        }
    }

    public static function unsubscribeFromTopic(string $subscription_arn = null)
    {
        Log::debug('unsubscribeFromTopic ' . '$subscription_arn = ' . $subscription_arn);
        if (!$subscription_arn) {
            return false;
        }
        try {
            $sns = AWS::createClient('sns');
            $sns->unsubscribe([
                'SubscriptionArn' => $subscription_arn
            ]);
        } catch (SnsException $e) {
            Log::error($e);

            return false;
        }

        return true;
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

    public static function deleteAllFileAndDir($directory)
    {
        $paths = glob($directory);

        foreach ($paths as $dirPath) {
            if (!is_dir($dirPath)) {
                throw new InvalidArgumentException("$dirPath must be a directory");
            }
            if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
                $dirPath .= '/';
            }
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    self::deleteDir($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($dirPath);
        }
    }
}