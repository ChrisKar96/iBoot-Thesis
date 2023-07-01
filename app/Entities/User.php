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
 * Class User
 *
 * @OA\Schema(
 *     title="User"
 * )
 * @OA\Tag(
 *     name="User"
 * )
 * @OA\RequestBody(
 *     request="User",
 *     description="User object that needs to be added",
 *     @OA\JsonContent(ref="#/components/schemas/User"),
 *     @OA\MediaType(
 *         mediaType="application/x-www-form-urlencoded",
 *         @OA\Schema(ref="#/components/schemas/User")
 *     )
 * )
 */
class User extends Entity
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
     * 	   maxLength=40,
     * )
     */
    private $name;

    /**
     * @OA\Property(
     *     description="email",
     *     title="email",
     *     type="string",
     * 	   format="-",
     * 	   nullable=false,
     * 	   maxLength=320,
     * )
     */
    private $email;

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

    /**
     * @OA\Property(
     *     description="username",
     *     title="username",
     *     type="string",
     * 	   format="-",
     * 	   nullable=false,
     * 	   maxLength=40,
     * )
     */
    private $username;

    /**
     * @OA\Property(
     *     description="password",
     *     title="password",
     *     type="string",
     * 	   format="-",
     * 	   nullable=false,
     * 	   maxLength=255,
     * )
     */
    private $password;

    /**
     * @OA\Property(
     *     description="isAdmin",
     *     title="isAdmin",
     *     type="integer",
     * 	   format="-",
     * 	   nullable=false,
     * 	   maxLength=1,
     * )
     */
    private $isAdmin;

    /**
     * @OA\Property(
     *     description="verifiedEmail",
     *     title="verifiedEmail",
     *     type="integer",
     * 	   format="-",
     * 	   nullable=false,
     * 	   maxLength=1,
     * )
     */
    private $verifiedEmail;

    /**
     * @OA\Property(
     *     description="created_at",
     *     title="created_at",
     *     type="string",
     * 	   format="-",
     * 	   nullable=true,
     * )
     */
    private $created_at;

    /**
     * @OA\Property(
     *     description="updated_at",
     *     title="updated_at",
     *     type="string",
     * 	   format="-",
     * 	   nullable=true,
     * )
     */
    private $updated_at;

    /**
     * @OA\Property(
     *     description="labs",
     *     title="labs",
     *     type="array",
     *     @OA\Items(
     *         type="integer",
     *         title="lab id",
     *     )
     * )
     */
    private $labs;
}
