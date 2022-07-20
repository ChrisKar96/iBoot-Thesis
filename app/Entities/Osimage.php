<?php

namespace iBoot\Entities;

use CodeIgniter\Entity\Entity;

/**
 * Class OsImage
 *
 * @OA\Schema(
 *     title="OsImage"
 * )
 *
 * @OA\Tag(
 *     name="OsImage"
 * )
 *
 * @OA\RequestBody(
 *     request="Osimage",
 *     description="OsImage object",
 *     @OA\JsonContent(ref="#/components/schemas/Osimage"),
 *     @OA\MediaType(
 *         mediaType="application/x-www-form-urlencoded",
 *         @OA\Schema(ref="#/components/schemas/Osimage")
 *     )
 * )
 */
class Osimage extends Entity
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
     * 	   maxLength=30,
     * )
     */
    private $name;

    /**
     * @OA\Property(
     *     description="ipxe_entry",
     *     title="ipxe_entry",
     *     type="string",
     * 	   format="-",
     * 	   nullable=false,
     * )
     */
    private $ipxe_entry;
}
