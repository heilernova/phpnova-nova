<?php
namespace Phpnova\Nova\Http;

class Request
{
    /**
     * URL of the HTTP request
     */
    public readonly string $url;

    /**
     * Method of HTTP request
     */
    public readonly string $method;

    /**
     * HTTP request content
     */
    public readonly mixed $body;

    /**
     * Array of files sent in the HTTP query
     * @var HttpFile[] 
     * */
    public readonly array $files;
    
    /** URL parameters */
    public readonly array $params;

    /**
     * Query parameter
    */
    public readonly array $queryParams;

    /**
     * IP from which the HTTP request is being made
     * */
    public readonly string $ip;

    /** 
     * Device from which the HTTP request is being made
     * @return 'desktop'|'mobile'|'table'
     */
    public readonly string $device;

    /** 
     * Name of the platform from which the HTTP request is being made
     * Example: Whindows, iPhone, Android
     * */
    public readonly string $platform;

    public function __construct()
    {
        $this->url = $_ENV['nv']['request']['url'];
        $this->method = $_ENV['nv']['request']['method'];
        $this->ip = $_ENV['nv']['request']['ip'];
        $this->body = $_ENV['nv']['request']['body'];
        $this->files = $_ENV['nv']['request']['files'];
        $this->params = $_ENV['nv']['request']['params'];
        $this->queryParams = $_ENV['nv']['request']['queryParams'];
        $this->platform = $_ENV['nv']['request']['platform'];
        $this->device = $_ENV['nv']['request']['device'];
    }
}