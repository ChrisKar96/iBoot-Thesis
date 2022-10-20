<?php

namespace iBoot\Entities;

use CodeIgniter\Entity\Entity;
use OpenApi\Annotations as OA;

/**
 * Class Lab
 *
 * @OA\Schema(
 *     title="Lab"
 * )
 *
 * @OA\Tag(
 *     name="Lab"
 * )
 *
 * @OA\RequestBody(
 *     request="Lab",
 *     description="Lab object",
 *     @OA\JsonContent(ref="#/components/schemas/Lab"),
 *     @OA\MediaType(
 *         mediaType="application/x-www-form-urlencoded",
 *         @OA\Schema(ref="#/components/schemas/Lab")
 *     )
 * )
 */
class Lab extends Entity
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
     *     description="address",
     *     title="address",
     *     type="string",
     * 	   format="-",
     * 	   nullable=true,
     * 	   maxLength=50,
     * )
     */
    private $address;

    /**
     * @OA\Property(
     *     description="phone",
     *     title="phone",
     *     type="string",
     * 	   format="-",
     * 	   nullable=true,
     * 	   maxLength=15,
     * )
     */
    private $phone;
}
