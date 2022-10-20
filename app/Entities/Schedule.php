<?php

namespace iBoot\Entities;

use CodeIgniter\Entity\Entity;
use OpenApi\Annotations as OA;

/**
 * Class Schedule
 *
 * @OA\Schema(
 *     title="Schedule"
 * )
 *
 * @OA\Tag(
 *     name="Schedule"
 * )
 *
 * @OA\RequestBody(
 *     request="Schedule",
 *     description="Schedule object",
 *     @OA\JsonContent(ref="#/components/schemas/Schedule"),
 *     @OA\MediaType(
 *         mediaType="application/x-www-form-urlencoded",
 *         @OA\Schema(ref="#/components/schemas/Schedule")
 *     )
 * )
 */
class Schedule extends Entity
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
     *     description="time_from",
     *     title="time_from",
     *     type="string",
     * 	   format="-",
     * 	   nullable=true,
     * )
     */
    private $time_from;

    /**
     * @OA\Property(
     *     description="time_to",
     *     title="time_to",
     *     type="string",
     * 	   format="-",
     * 	   nullable=true,
     * )
     */
    private $time_to;

    /**
     * @OA\Property(
     *     description="day_of_week",
     *     title="day_of_week",
     *     type="integer",
     * 	   format="-",
     * 	   nullable=true,
     * 	   maxLength=3,
     * )
     */
    private $day_of_week;

    /**
     * @OA\Property(
     *     description="date",
     *     title="date",
     *     type="string",
     * 	   format="date",
     * 	   nullable=true,
     * )
     */
    private $date;

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
     *     description="group_id",
     *     title="group_id",
     *     type="integer",
     * 	   format="-",
     * 	   nullable=false,
     * 	   maxLength=10,
     * )
     */
    private $group_id;

    /**
     * @OA\Property(
     *     description="isActive",
     *     title="isActive",
     *     type="integer",
     * 	   format="-",
     * 	   nullable=false,
     * 	   maxLength=1,
     * )
     */
    private $isActive;

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
}
