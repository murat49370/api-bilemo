<?php


namespace App\Controller;

use OpenApi\Annotations as OA;


/**
 *
 *
 * @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="Ressource ID",
 *          required=true,
 *          @OA\Schema(type="integer")
 *     )
 *
 *
 * @OA\Response(
 *     response="NotFound",
 *     description="This resource does not exist.",
 *     @OA\JsonContent(
 *          @OA\Property(property="message", type="string", example="This resource does not exist.")
 *     )
 * )
 *
 * @OA\Response(
 *     response="InvalidID",
 *     description="Invalid ID supplied",
 *     @OA\JsonContent(
 *          @OA\Property(property="message", type="string", example="Invalid ID supplied")
 *     )
 * )
 *
 * @OA\Response(
 *     response="TokenNotFound",
 *     description="Token not found",
 *     @OA\JsonContent(
 *          @OA\Property(property="message", type="string", example="JWT Token not found")
 *     )
 * )
 *
 * @OA\Response(
 *     response="ResourceDelete",
 *     description="Resource delete or notfound",
 *     @OA\JsonContent(
 *          @OA\Property(property="message", type="string", example="JWT Token not found")
 *     )
 * )
 *
 */
class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{

}