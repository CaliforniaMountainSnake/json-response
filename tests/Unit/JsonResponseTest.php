<?php

namespace Tests\Unit;

use CaliforniaMountainSnake\JsonResponse\JsonResponse;
use PHPUnit\Framework\TestCase;

class JsonResponseTest extends TestCase
{
    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testToJson(): void
    {
        $info = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];

        $json = JsonResponse::good($info)->toJson();
        $this->assertEquals('{"is_ok":"true","body":{"key1":"value1","key2":"value2"}}', $json);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testHeaders(): void
    {
        $jr = JsonResponse::good(['test'])->withCors()->headers(['custom_name' => 'custom_value']);
        $this->assertSame(\count($jr->getHeaders()), 4);
    }
}
