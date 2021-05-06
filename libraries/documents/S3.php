<?php

namespace App\libraries\documents;

use Aws\S3\S3ClientInterface;

class S3 extends DocumentAbstract
{
    private $bucket;
    private $client;

    public function __construct(S3ClientInterface $client, $bucket, $prefix = '')
    {
        $this->client = $client;
        $this->bucket = $bucket;
        $this->setPathPrefix($prefix);
    }

    public function read($path)
    {
        $options = [
            'Bucket' => $this->bucket,
            'Key' => $this->applyPathPrefix($path),
        ];

        $command = $this->client->getCommand('getObject', $options);

        $response = $this->client->execute($command);

        return $response;
    }

    public function write($path, $contents)
    {
        $key = $this->applyPathPrefix($path);

        return $this->client->upload($this->bucket, $key, $contents);
    }

    public function rename($path, $newpath)
    {
        if (!$this->copy($path, $newpath)) {
            return false;
        }

        return $this->delete($path);
    }

    public function copy($path, $newpath)
    {
        $command = $this->client->getCommand(
            'copyObject',
            [
                'Bucket' => $this->bucket,
                'Key' => $this->applyPathPrefix($newpath),
                'CopySource' => rawurlencode($this->bucket . '/' . $this->applyPathPrefix($path)),
            ]
        );

        return $this->client->execute($command);
    }

    public function delete($path)
    {
        $location = $this->applyPathPrefix($path);

        $command = $this->client->getCommand(
            'deleteObject',
            [
                'Bucket' => $this->bucket,
                'Key' => $location,
            ]
        );

        return $this->client->execute($command);
    }
}
