<?php
namespace Phpnova\Nova\Http;

use Exception;
use Phpnova\Nova\Bin\ErrorCore;
use SplFileInfo;

class Response
{
    public readonly mixed $body;
    public readonly int $stadus;
    public readonly array $headers;

    /**
     * @param 'json'|'html'|'file'|'text' $type
     */
    public function __construct(mixed $body, int $status = 200, private string $type = 'json')
    {
        $this->body = $body;
        $this->stadus = $status;
        $this->headers = [];

        if ($type == 'file' && !file_exists($body)){
            throw new ErrorCore("Ruta del archivo erronea");
        }
    }

    public function clone($body = null, $stadus = null, $type = null ): Response
    {
        $body = $body ? $body : $this->body;
        $stadus = $stadus ? $stadus : $this->stadus;
        $type = $type ? $type : $this->type;
        return new Response($body, $stadus, $type);
    }

    public function getData(): array
    {

        $body = $this->mapBody();

        return [
            'body' => $body,
            'content-type' => $this->getContentType(),
            'headers' => $this->headers,
            'status' => $this->stadus
        ];
    }

    private function mapBody():mixed
    {
        switch ($this->type) {
            case 'json':
                $json = json_encode($this->body);
                if (json_last_error() != JSON_ERROR_NONE) {
                    throw new Exception("Error al covertir el formato JSON: " . json_last_error_msg());
                }
                return $json;
                break;

            case 'file': return file_get_contents($this->body);
            default: return $this->body;
        }
    }

    private function getContentType(): string {
        switch ($this->type) {
            case 'json': return "application/json; charset=utf-8";
            case 'text': return "text/plain";
            case 'file': return HttpFuns::getContentType((new SplFileInfo($this->body))->getExtension());
            default: return $this->contentType = "text/html; charset=utf-8";
        }
    }
}