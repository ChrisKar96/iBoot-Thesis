<?php

/**
 * This file is part of iBoot.
 *
 * (c) 2021 Christos Karamolegkos <iboot@ckaramolegkos.gr>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace iBoot\Entities;

use CodeIgniter\Entity\Entity;
use OpenApi\Annotations as OA;

/**
 * Class IpxeBlock
 *
 * @OA\Schema(
 *     title="IpxeBlock"
 * )
 * @OA\Tag(
 *     name="IpxeBlock"
 * )
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
