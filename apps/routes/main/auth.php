<?php


return [


/**
 * @OA\Get(
 *   path="/v1/me",
 *   summary="Get current user",
 *   tags={"Auth"},
 *   @OA\Response(
 *     response=200,
 *     description="Currently logged in user data",
 *     @OA\JsonContent(
 *           @OA\Property(property="dataPayload", type="object",ref="#/components/schemas/User"))
 *     ),
 *   ),
 *   @OA\Response(
 *     response=401,
 *     description="Unauthorized",
 *     @OA\JsonContent(
 *          @OA\Property(property="errorPayload", type="object",ref="#/components/schemas/Unauthorized"))
 *      )
 *   )
 * )
 */
    'GET me' => 'auth/me'
];