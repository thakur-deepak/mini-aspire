<?php

namespace App\Components;

use Storage;

class AwsS3
{
    public function upload($key, $file)
    {
        return Storage::disk('s3')->put($key, $file);
    }

    public function get($key)
    {
        return Storage::disk('s3')->get($key);
    }

    public function delete($key)
    {
        return Storage::disk('s3')->delete($key);
    }

    public function urlValidate($file)
    {
        $response_attachment = $this->getCloudUrl(
            $file,
            $file,
            env("AWS_S3_FILE_ACCESS_AS"),
            env("AWS_S3_URL_EXPIRY_TIME")
        );
        return is_object($response_attachment) ? (string) $response_attachment->getUri() : null;
    }

    public function getCloudUrl($aws_file_name, $file_name, $content_disposition, $expiry_time = 10)
    {
        $s3_storage = \Storage::disk('s3');
        $client = $s3_storage->getDriver()->getAdapter()->getClient();
        $expiry = "+" . $expiry_time . " minutes";
        $get_file_object = $client->getCommand(
            'GetObject',
            [
            'Bucket' => env("AWS_BUCKET"),
            'Key' => $aws_file_name,
            'ResponseContentDisposition' => "$content_disposition; filename=\"" . $file_name . '"',
            ]
        );
        return $client->createPresignedRequest($get_file_object, $expiry);
    }
}
