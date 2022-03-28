<?php
declare(strict_types=1);

namespace Api\Dto;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    public function __construct($data = null, int $status = 200, array $headers = [], bool $json = false)
    {
        !$json ?: $data = json_decode($data);
        $data = [
            'success' => $status >= 200 and $status < 300,
            'status' => $status,
            'data' => $data
        ];
        parent::__construct($data, $status, $headers);
    }
}
