<?php

namespace CaliforniaMountainSnake\JsonResponse;

use Illuminate\Http\Response;

/**
 * The json-response object for uniformity of responses api.
 */
class JsonResponse implements HttpCodes
{
    public const IS_OK = 'is_ok';
    public const ERRORS = 'errors';
    public const BODY = 'body';

    /**
     * @var bool
     */
    protected $isOk;

    /**
     * @var int
     */
    protected $httpCode;

    /**
     * @var array
     */
    protected $errors;

    /**
     * @var array|null
     */
    protected $body;

    /**
     * @var string[]
     */
    protected $headers = [];

    /**
     * JsonResponse constructor.
     *
     * @param int        $_http_code
     * @param array      $errors
     * @param array|null $body
     */
    public function __construct(int $_http_code, array $errors = [], array $body = null)
    {
        $this->isOk = true;
        $this->httpCode = $_http_code;
        $this->errors = $errors;
        $this->body = $body;

        if (!empty($errors)) {
            $this->isOk = false;
        }

        $this->withApplicationJson();
    }

    /**
     * Allow cross-origin requests.
     *
     * @return JsonResponse
     */
    public function withCors(): self
    {
        $this->headers([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'X-Requested-With, Content-Type, X-Token-Auth, Authorization',
        ]);

        return $this;
    }

    /**
     * Add custom headers to the response.
     *
     * @param string[] $_headers Array with headers ['header_name' => 'header_value']
     *
     * @return JsonResponse
     */
    public function headers(array $_headers): self
    {
        $this->headers = \array_merge($this->headers, $_headers);
        return $this;
    }

    /**
     * @return Response
     */
    public function make(): Response
    {
        try {
            $response = new Response($this->toJson(), $this->httpCode);
            return $response->withHeaders($this->headers);
        } catch (\LogicException $e) {
            throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param array $body
     * @param int   $_http_code
     *
     * @return JsonResponse
     */
    public static function good(array $body, int $_http_code = 200): self
    {
        return new self($_http_code, [], $body);
    }

    /**
     * @param array $errors
     * @param int   $_http_code
     *
     * @return JsonResponse
     */
    public static function error(array $errors, int $_http_code = 400): self
    {
        return new self ($_http_code, $errors, null);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $arr = [
            self::IS_OK => $this->isOk ? 'true' : 'false',
        ];

        if (!empty($this->errors)) {
            $arr[self::ERRORS] = $this->errors;
        }
        if ($this->body !== null) {
            $arr[self::BODY] = $this->body;
        }

        return $arr;
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return \json_encode($this->toArray());
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return $this->toArray();
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * Add "Content-Type: application/json" header to the response.
     *
     * @return JsonResponse
     */
    private function withApplicationJson(): self
    {
        $this->headers([
            'Content-Type' => 'application/json',
        ]);

        return $this;
    }

    //------------------------------------------------------------------------------------------------------------------
    // Getters.
    //------------------------------------------------------------------------------------------------------------------

    /**
     * @return bool
     */
    public function isOk(): bool
    {
        return $this->isOk;
    }

    /**
     * @return int
     */
    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return array|null
     */
    public function getBody(): ?array
    {
        return $this->body;
    }

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
