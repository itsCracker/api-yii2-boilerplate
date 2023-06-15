<?php 
return[
 /**
 *@OA\Schema(
 *  schema="ErrorValidate",
 *  @OA\Property(property="statusCode", type="integer", example=422 ),
 *  @OA\Property(property="Message", type="string", example="Resource Not found" ),
 *  @OA\Property(property="Name", type="string", example="ErrorValidate" )
 * )
 */

 /**
 *@OA\Schema(
 *  schema="Unauthorized",
 *  @OA\Property(property="statusCode", type="integer", example=401 ),
 *  @OA\Property(property="Message", type="string", example="Resource Not found" ),
 *  @OA\Property(property="Name", type="string", example="Unauthorized" )
 * )
 */


 /**
 *@OA\Schema(
 *  schema="Error",
 *  @OA\Property(property="statusCode", type="integer", example=404 ),
 *  @OA\Property(property="errorMessage", type="string", example="Resource Not found" )
 * )
 */

  /**
 * @OA\Schema(
 *   schema="About",
 *   type="object",
 *   required={"name", "description", "version", "baseUrl"},
 *   allOf={
 *     @OA\Schema(
 *       @OA\Property(property="name", type="string",description="Name App"),
 *       @OA\Property(property="description", type="string",description="Detail Information App"),
 *       @OA\Property(property="version", type="string",description="Version APP"),
 *       @OA\Property(property="baseUrl", type="string",description="Base Url APP")
 *     )
 *   }
 * )
 */
 ];