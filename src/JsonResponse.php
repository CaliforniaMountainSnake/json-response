<?php

namespace CaliforniaMountainSnake\JsonResponse;

use Illuminate\Http\Response;

/**
 * The json-response object for uniformity of responses api.
 */
class JsonResponse
{
    public const IS_OK  = 'is_ok';
    public const ERRORS = 'errors';
    public const BODY   = 'body';

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

    public function __construct(int $_http_code, array $errors = [], array $body = null)
    {
        $this->isOk     = true;
        $this->httpCode = $_http_code;
        $this->errors   = $errors;
        $this->body     = $body;

        if (!empty($errors)) {
            $this->isOk = false;
        }
    }

    public static function good(array $body, int $_http_code = 200): self
    {
        return new self($_http_code, [], $body);
    }

    public static function error(array $errors, int $_http_code = 400): self
    {
        return new self ($_http_code, $errors, null);
    }

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

    public function toJson(): string
    {
        return \json_encode($this->toArray());
    }

    public function __debugInfo(): array
    {
        return $this->toArray();
    }

    /**
     * @return Response
     */
    public function make(): Response
    {
        try {
            return new Response($this->toJson(), $this->httpCode);
        } catch (\LogicException $e) {
            throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
