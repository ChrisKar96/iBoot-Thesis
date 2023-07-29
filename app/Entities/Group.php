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
use CodeIgniter\I18n\Time;
use iBoot\Models\ScheduleModel;
use OpenApi\Annotations as OA;

/**
 * Class Group
 *
 * @OA\Schema(
 *     title="Group"
 * )
 * @OA\Tag(
 *     name="Group"
 * )
 * @OA\RequestBody(
 *     request="Group",
 *     description="Group object",
 *     @OA\JsonContent(ref="#/components/schemas/Group"),
 *     @OA\MediaType(
 *         mediaType="application/x-www-form-urlencoded",
 *         @OA\Schema(ref="#/components/schemas/Group")
 *     )
 * )
 */
class Group extends Entity
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
     *     description="image_server_ip",
     *     title="image_server_ip",
     *     type="string",
     * 	   format="-",
     * 	   nullable=false,
     * 	   maxLength=15,
     * )
     */
    private $image_server_ip;

    /**
     * @OA\Property(
     *     description="image_server_path_prefix",
     *     title="image_server_path_prefix",
     *     type="string",
     * 	   format="-",
     * 	   nullable=false,
     * 	   maxLength=50,
     * )
     */
    private $image_server_path_prefix;

    /**
     * @OA\Property(
     *     description="computers",
     *     title="computers",
     *     type="array",
     *     @OA\Items(
     *         type="integer",
     *         title="computer id",
     *     )
     * )
     */
    private $computers;

    public function getScheduleObj(Time $curtime)
    {
        $scheduleModel = new ScheduleModel();
        $scheduleModel->builder()->where('group_id', $this->attributes['id'])->where('isActive', true);
        $scheduleModel->builder()->where('time_from <=', $curtime->toTimeString())->where('time_to >=', $curtime->toTimeString());
        $scheduleModel->builder()->where('date', $curtime->toDateString());
        $scheduleModel->builder()->orderBy('id', 'DESC');

        $schedule = $scheduleModel->first();

        if (empty($schedule)) {
            $scheduleModel->builder()->where('group_id', $this->attributes['id'])->where('isActive', true);
            $scheduleModel->builder()->where('time_from <=', $curtime->toTimeString())->where('time_to >=', $curtime->toTimeString());
            $scheduleModel->builder()->where('day_of_week', (int) $curtime->getDayOfWeek() - 1);
            $scheduleModel->builder()->orderBy('id', 'DESC');

            $schedule = $scheduleModel->first();
        }

        return $schedule;
    }
}
