<?php
namespace Phpnova\Nova\Http;

class HttpRequest
{
    public readonly string $url;
    public readonly string $method;
    public readonly mixed $body;
    /** @var HttpFile[] */
    public readonly array $files;
    public readonly array $params;
    public readonly array $paramsURL;
    public readonly array $queryParams;
    public readonly string $ip;
    public readonly string $device;
    public readonly string $platform;

    public function __construct()
    {
        $this->url = $_ENV['nvx']['request']['url'];
        $this->method = $_ENV['nvx']['request']['method'];
        $this->ip = $_ENV['nvx']['request']['ip'];
        $this->body = $_ENV['nvx']['request']['body'];
        $this->files = $_ENV['nvx']['request']['files'];
        $this->params = $_ENV['nvx']['request']['params'];
        $this->paramsURL = $_ENV['nvx']['request']['query-params'];
        $this->queryParams = $_ENV['nvx']['request']['query-params'];
        $this->platform = $_ENV['nvx']['request']['platform'];
        $this->device = $_ENV['nvx']['request']['device'];
    }
}