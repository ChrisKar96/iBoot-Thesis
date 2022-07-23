<?php

namespace iBoot\Entities;

use CodeIgniter\Entity\Entity;

/**
 * Class IpxeBlock
 *
 * @OA\Schema(
 *     title="IpxeBlock"
 * )
 *
 * @OA\Tag(
 *     name="IpxeBlock"
 * )
 *
 * @OA\RequestBody(
 *     request="IpxeBlock",
 *     description="IpxeBlock object",
 *     @OA\JsonContent(ref="#/components/schemas/IpxeBlock"),
 *     @OA\MediaType(
 *         mediaType="application/x-www-form-urlencoded",
 *         @OA\Schema(ref="#/components/schemas/IpxeBlock")
 *     )
 * )
 */
class IpxeBlock extends Entity
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
     *     description="ipxe_block",
     *     title="ipxe_block",
     *     type="string",
     * 	   format="-",
     * 	   nullable=false,
     * )
     */
    private $ipxe_block;
}
