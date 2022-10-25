<?php
namespace Phpnova\Nova\Http;

class HttpRequest
{
    public readonly string $url;
    public readonly string $method;
    public readonly string $ip;
    public readonly mixed $body;
    public readonly array $params;
    public readonly array $paramsURL;
    public readonly string $device;
    public readonly string $platform;

    public function __construct()
    {
        $this->url = $_ENV['nvx']['request']['url'];
        $this->method = $_ENV['nvx']['request']['method'];
        $this->ip = $_ENV['nvx']['request']['ip'];
        $this->body = $_ENV['nvx']['request']['body'];
        $this->params = $_ENV['nvx']['request']['params'];
        $this->paramsURL = $_ENV['nvx']['request']['params-url'];
        $this->platform = $_ENV['nvx']['request']['platform'];
        $this->device = $_ENV['nvx']['request']['device'];
    }
}