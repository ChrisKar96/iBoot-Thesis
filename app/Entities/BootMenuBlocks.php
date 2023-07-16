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
 * Class BootMenuBlocks
 *
 * @OA\Schema(
 *     title="BootMenuBlocks"
 * )
 * @OA\Tag(
 *     name="BootMenu"
 * )
 * @OA\RequestBody(
 *     request="BootMenuBlocks",
 *     description="BootMenuBlocks object",
 *     @OA\JsonContent(ref="#/components/schemas/BootMenuBlocks"),
 *     @OA\MediaType(
 *         mediaType="application/x-www-form-urlencoded",
 *         @OA\Schema(ref="#/components/schemas/BootMenuBlocks")
 *     )
 * )
 */
class BootMenuBlocks extends Entity
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
     *     description="boot_menu_id",
     *     title="boot_menu_id",
     *     type="integer",
     * 	   format="-",
     * 	   nullable=false,
     * 	   maxLength=10,
     * )
     */
    private $boot_menu_id;

    /**
     * @OA\Property(
     *     description="block_id",
     *     title="block_id",
     *     type="integer",
     * 	   format="-",
     * 	   nullable=false,
     * 	   maxLength=10,
     * )
     */
    private $block_id;

    /**
     * @OA\Property(
     *     description="ipxe_block",
     *     title="ipxe_block",
     *     type="string",
     * 	   format="-",
     * 	   nullable=true,
     * )
     */
    private $ipxe_block;
}
