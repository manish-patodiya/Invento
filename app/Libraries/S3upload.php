<?php
namespace App\Libraries;

include "./Vendor/autoload.php";
use Aws\S3\S3Client;

class S3upload
{
    private $s3;
    public function __construct()
    {
        $awsAccessKey = 'AKIAX5XNO5URFIR4TEF5';
        $awsSecretKey = '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';
        $this->S3 = S3Client::factory([
            'version' => 'latest',
            'region' => 'ap-south-1',
            'credentials' => [
                'key' => $awsAccessKey,
                'secret' => $awsSecretKey,
            ],
        ]);
    }

    public function upload($image, $fileName, $folderName)
    {

        $bucket = 'ssc-inventory';
        $info = pathinfo($image);
        $extension = $info['extension'];
        $name = $info['basename'];
        $tmp = $image;
        $file_path = $name;
        $actual_image_name = $folderName . '/' . $fileName;
        $sha256 = hash_file("sha256", $image);
        $result = $this->S3->putObject(array(
            'Bucket' => $bucket,
            'Key' => $actual_image_name,
            'SourceFile' => $tmp,
            'ContentLength' => 1073741824,
            // 'ACL' => 'public-read',
            "ContentSHA256" => $sha256,
        ));
        return $result['ObjectURL'] . "\n";
    }

    public function upload1($image, $fileName, $folderName)
    {
        $awsAccessKey = 'AKIAX5XNO5URFIR4TEF5';
        $awsSecretKey = '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';
        $s3 = new S3Client([
            'version' => 'latest',
            'region' => 'ap-south-1',
            'credentials' => [
                'key' => $awsAccessKey,
                'secret' => $awsSecretKey,
            ],
        ]);

        $bucket = 'invento';
        $info = pathinfo($image);
        $extension = $info['extension'];
        $name = $info['basename'];
        $tmp = $image;
        $file_path = $name;

        $info = pathinfo($name);
        $ext = $info['extension'];
        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg", "PNG", "JPG", "JPEG", "GIF", "BMP", "html", "HTML", 'mp4', 'mp3');
        if (strlen($name) > 0) {
            if (in_array($ext, $valid_formats)) {
                /*if($size<(1024*1024)) { */
                $actual_image_name = $folderName . '/' . $fileName;

                $result = $s3->putObject(array(
                    'Bucket' => $bucket . "/$folderName/$fileName",
                    'Key' => 'data_from_file.txt',
                    'SourceFile' => $tmp,
                ));
                print_r($result);

                // if ($s3->putObjectFile($tmp, $bucket, $actual_image_name, )) {
                //     //return $s3file='http://'.$bucket.'.s3.amazonaws.com/'.$actual_image_name;
                //     return true;
                // } else {
                //     //return 'Issue in saving in Bucket!';
                //     return false;
                // }

                /*} else{
            return 'Size Issue! - Less than 1024*1024';
            }*/
            }
        }
    }

}