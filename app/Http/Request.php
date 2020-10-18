<?php

namespace App\Http;

use Exception;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Client;

/**
 * При необходимости [[send]] можно сделать абстрактным и избавиться от зависимости от библиотеки.
 * Или начать принимать Sender в конструктор и пробрасывать вызов $this->sender->send()
 */
class Request
{
    protected $method = "GET";
    protected $url;
    protected $headers = [];
    protected $params = [];

    protected $response;

    public function __construct($url = false)
    {
        if ($url) {
            $this->url = $url;
        }
    }

    public function getParam($name)
    {
        return isset($this->params[$name]) ? $this->params[$name] : null;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }

    public function setParams($attrs)
    {
        foreach ($attrs as $key => $attr) {
            $this->setParam($key, $attr);
        }

        return $this;
    }

    public function beforeExecute()
    {
        if (count($this->getParams())) {
            $this->method = 'POST';
        }

        return $this;
    }

    public function execute()
    {
        if (!$this->url) {
            throw new Exception('Url not defined!');
        }

        $this->beforeExecute();

        $options = [
            'headers' => $this->getHeaders(),
            'body' => $this->getBody(),
        ];

        $response = $this->send($this->method, $this->url, $options['headers'], $options['body']);

        $this->response = (string)$response->getBody();

        $this->afterExecute();

        return $this->response;
    }

    public function afterExecute()
    {
        return $this;
    }

    protected function getBody()
    {
        //@todo
        return '';
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    public function send($type, $url, $headers, $body, $version = '1.1')
    {
        $client = new Client([
            'http_errors' => false,
            'allow_redirects' => false,
        ]);

        $request = new GuzzleRequest($type, $url, $headers, $body, $version);

        /**
         * @var Object $response
         */
        return $client->send($request);
    }
}