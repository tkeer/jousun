<?php

namespace App\libraries\documents;

use GuzzleHttp\Client;

class Dropbox extends DocumentAbstract
{
    private $appKey;
    private $appSecret;
    private $client;

    public function __construct(Client $client, $appKey, $appSecret, $prefix = '')
    {
        $this->client = $client;
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
        $this->setPathPrefix($prefix);
    }

    public function read($path)
    {
        $stream = $this->client->download($path);

        return stream_get_contents($stream);
    }

    public function write($path, $contents)
    {
        $arguments = [
            'path' => $this->applyPathPrefix($path),
            'mode' => 'add',
        ];

        $response = $this->request('files/upload', $arguments, $contents);

        $metadata = json_decode($response->getBody(), true);

        return $metadata;
    }

    public function rename($path, $newPath)
    {
        $parameters = [
            'from_path' => $this->applyPathPrefix($path),
            'to_path' => $this->applyPathPrefix($newPath),
        ];

        return $this->request('files/move_v2', $parameters);
    }

    public function copy($path, $newpath)
    {
        $parameters = [
            'from_path' => $this->applyPathPrefix($path),
            'to_path' => $this->applyPathPrefix($newpath),
        ];

        return $this->request('files/copy_v2', $parameters);
    }

    public function delete($path)
    {
        $parameters = [
            'path' => $this->applyPathPrefix($path),
        ];

        return $this->request('files/delete', $parameters);
    }

    private function getEndpointUrl($subdomain, $endpoint)
    {
        return "https://{$subdomain}.dropboxapi.com/2/{$endpoint}";
    }

    private function request($endpoint, array $arguments, $content = '')
    {
        $headers['Authorization'] = 'Basic ' . base64_encode($this->appKey . ':' . $this->appSecret);
        $headers['Dropbox-API-Arg'] = json_encode($arguments);
        $endpoint = $this->getEndpointUrl('content', $endpoint);

        $response = $this->client->post($endpoint, [
            'headers' => $headers,
            'body' => $content,
        ]);

        return $response;
    }
}
