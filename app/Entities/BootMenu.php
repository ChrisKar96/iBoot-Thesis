<?php

namespace iBoot\Entities;

use CodeIgniter\Entity\Entity;

/**
 * Class BootMenu
 *
 * @OA\Schema(
 *     title="BootMenu"
 * )
 *
 * @OA\Tag(
 *     name="BootMenu"
 * )
 *
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
}
