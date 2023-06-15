<?php
return[

        /**
     * @OA\Get(
     *     path="/v1/type",
     *     summary="Get list all available users profiles",
     *     tags={"Type"},
     *     @OA\Response(
     *         response=200,
     *         description="successful",
     *         @OA\Schema(
     *            type="array",
     *            @OA\Items(ref="#/components/schemas/Type")
     *         )
     *     ),
     *     @OA\Response(
     *        response=401,
     *        description="Unauthorized",
     *        @OA\Schema(ref="#/schemas/Unauthorized")
     *     )
     * )
     */
    'GET type' => 'mode/index',

        /**
     * @OA\Post(
     *     path="/v1/type",
     *     summary="Create a certain user Profile",
     *     tags={"Type"},
     *     @OA\RequestBody(
     *     description="Create a user Profile",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/CreateType"),
     *     @OA\MediaType(
     *         mediaType="application/xml",
     *         @OA\Schema(ref="#/components/schemas/CreateType")
     *     )
     * ),
     *     @OA\Response(
     *         response=201,
     *         description="successful",
     *         @OA\JsonContent(ref="#/components/schemas/CreateType"),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="ValidateErrorException",
     *         @OA\JsonContent(ref="#/components/schemas/Type")
     *     ),
     *     
     * )
     */
    'POST type' => 'mode/create',

    /**
     * @OA\Put(
     *     path="/v1/type/{mode_id}",
     *     summary="Update a user profile ",
     *     tags={"Type"},
     *       @OA\Parameter(
     *         name="Id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *   
     *     @OA\RequestBody(
     *        required=true,
     *        description="user profile value to be updated",
     *        @OA\JsonContent(ref="#/components/schemas/UpdateType")
     *     ),
     *    @OA\Response(
     *         response=202,
     *         description="successful",
     *         @OA\JsonContent(
     *          @OA\Property(property="dataPayload", type="object",
     *             @OA\Property(property="data", type="object",ref="#/components/schemas/UpdateType"),
     *             @OA\Property(property="toastMessage", type="string", example="Type updated succefully"),
     *             @OA\Property(property="toastTheme", type="string",example="success"),
     *          )
     *       )
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Resource not found",
     *         @OA\JsonContent(
     *           @OA\Property(property="errorPayload", type="object")
     *         )
     *     )
     * )
     */
    'PUT type/{mode_id}' => 'mode/update',
    
    /**
     * @OA\Get(
     *     path="/v1/type/{mode_id}",
     *     summary="Get a user profile by Id",
     *     tags={"Type"},
     *      @OA\Parameter(
     *         name="Id",
     *         in="path",
     *         description="user profile ID to be returned",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful",
     *        @OA\JsonContent(
     *           @OA\Property(property="dataPayload", type="object", ref="#/components/schemas/Type")
     *          )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found",
     *         @OA\JsonContent(
     *          @OA\Property(property="errorPayload", type="object")
     *          )
     *     )
     * )
     */
    'GET type/{mode_id}' => 'mode/view',

    /**
     * @OA\Delete(
     *     path="/v1/type/{mode_id}",
     *     summary="Delete a user profile",
     *     tags={"Type"},
     *       @OA\Parameter(
     *         name="Id",
     *         in="path",
     *         description="user profile id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             
     *         ),
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Status Delete",
     *         @OA\JsonContent(
     *              @OA\Property(property="dataPayload", type="object",ref="#/components/schemas/CreateType")
     *          )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found",
     *         @OA\JsonContent(
     *           @OA\Property(property="errorPayload", type="object")
     *         )
     *     ),
     *  
     * )
     */
    'DELETE type/{mode_id}' => 'mode/delete',
];
