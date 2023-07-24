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
use iBoot\Models\BootMenuBlocksModel;
use OpenApi\Annotations as OA;

/**
 * Class BootMenu
 *
 * @OA\Schema(
 *     title="BootMenu"
 * )
 * @OA\Tag(
 *     name="BootMenu"
 * )
 * @OA\RequestBody(
 *     request="BootMenu",
 *     description="BootMenu object",
 *     @OA\JsonContent(ref="#/components/schemas/BootMenu"),
 *     @OA\MediaType(
 *         mediaType="application/x-www-form-urlencoded",
 *         @OA\Schema(ref="#/components/schemas/BootMenu")
 *     )
 * )
 */
class BootMenu extends Entity
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
     *     description="description",
     *     title="description",
     *     type="string",
     * 	   format="-",
     * 	   nullable=false,
     * 	   maxLength=50,
     * )
     */
    private $description;

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

    public function getBootMenuBlockObjs()
    {
        $bootMenuModel = new BootMenuBlocksModel();

        return $bootMenuModel->where('boot_menu_id', $this->attributes['id'])->findAll();
    }
}
