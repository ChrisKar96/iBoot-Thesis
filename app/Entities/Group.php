<?php

namespace iBoot\Entities;

use CodeIgniter\Entity\Entity;

/**
 * Class Group
 *
 * @OA\Schema(
 *     title="Group"
 * )
 *
 * @OA\Tag(
 *     name="Group"
 * )
 *
 * @OA\RequestBody(
 *     request="Group",
 *     description="Group object",
 *     @OA\JsonContent(ref="#/components/schemas/Group"),
 *     @OA\MediaType(
 *         mediaType="application/x-www-form-urlencoded",
 *         @OA\Schema(ref="#/components/schemas/Group")
 *     )
 * )
 */
class Group extends Entity
{
    /**
     * @OA\Property(
     *     description="id",
     *     title="id",
     *     type="integer",
     * 	   format="-",
     * 	   nullable=false,
     * 	   maxLength=10,
     * )
     */
    private $id;

    /**
     * @OA\Property(
     *     description="name",
     *     title="name",
     *     type="string",
     * 	   format="-",
     * 	   nullable=false,
     * 	   maxLength=20,
     * )
     */
    private $name;

    /**
     * @OA\Property(
     *     description="image_server_ip",
     *     title="image_server_ip",
     *     type="string",
     * 	   format="-",
     * 	   nullable=false,
     * 	   maxLength=15,
     * )
     */
    private $image_server_ip;

    /**
     * @OA\Property(
     *     description="image_server_prefix_path",
     *     title="image_server_prefix_path",
     *     type="string",
     * 	   format="-",
     * 	   nullable=false,
     * 	   maxLength=50,
     * )
     */
    private $image_server_prefix_path;
}
