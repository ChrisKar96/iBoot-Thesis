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
use iBoot\Models\GroupModel;
use OpenApi\Annotations as OA;

/**
 * Class Computer
 *
 * @OA\Schema(
 *     title="Computer"
 * )
 * @OA\Tag(
 *     name="Computer"
 * )
 * @OA\RequestBody(
 *     request="Computer",
 *     description="Computer object",
 *     @OA\JsonContent(ref="#/components/schemas/Computer"),
 *     @OA\MediaType(
 *         mediaType="application/x-www-form-urlencoded",
 *         @OA\Schema(ref="#/components/schemas/Computer")
 *     )
 * )
 */
class Computer extends Entity
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
     * 	   nullable=true,
     * 	   maxLength=20,
     * )
     */
    private $name;

    /**
     * @OA\Property(
     *     description="uuid",
     *     title="uuid",
     *     type="string",
     * 	   format="-",
     * 	   nullable=false,
     * 	   maxLength=36,
     * )
     */
    private $uuid;

    /**
     * @OA\Property(
     *     description="mac",
     *     title="mac",
     *     type="string",
     * 	   format="-",
     * 	   nullable=false,
     * 	   maxLength=17,
     * )
     */
    private $mac;

    /**
     * @OA\Property(
     *     description="notes",
     *     title="notes",
     *     type="string",
     * 	   format="-",
     * 	   nullable=true,
     * )
     */
    private $notes;

    /**
     * @OA\Property(
     *     description="lab",
     *     title="lab",
     *     type="integer",
     * 	   format="-",
     * 	   nullable=true,
     * 	   maxLength=10,
     * )
     */
    private $lab;

    /**
     * @OA\Property(
     *     description="groups",
     *     title="groups",
     *     type="array",
     *     @OA\Items(
     *         type="integer",
     *         title="group id",
     *     )
     * )
     */
    private $groups;

    /**
     * @OA\Property(
     *     description="last_boot",
     *     title="last_boot",
     *     type="datetime",
     * 	   format="Y-m-d H:i:s",
     * 	   nullable=true,
     * )
     */
    private $last_boot;

    public function getGroupObjs(): array
    {
        $group_arr  = [];
        $groupModel = new GroupModel();

        foreach ($this->attributes['groups'] as $g) {
            $group_arr[] = $groupModel->find($g);
        }

        return $group_arr;
    }
}
