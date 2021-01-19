<?php


use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="API Bilemo", version="0.1")
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Bilemo API"
 * )
 * @OA\SecurityScheme(
 *     bearerFormat="JWT",
 *     securityScheme="bearer",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization",
 * )
 *
 *
 *
 */



