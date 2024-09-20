<?php
namespace App\Http\Controllers\Api;

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *         title="Laravel API Documentation",
 *         description="This is the API documentation for the Laravel project using Swagger",
 *         @OA\Contact(
 *             email="support@example.com"
 *         ),
 *         @OA\License(
 *             name="Apache 2.0",
 *             url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *         )
 *     ),
 *     @OA\Server(
 *         url=L5_SWAGGER_CONST_HOST,
 *         description="API Server"
 *     )
 * )
 */
class SwaggerController
{
    // This class is used only for Swagger documentation purposes
}
