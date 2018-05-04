<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 03/05/18
 * Time: 23:28
 */

namespace App\Tools;


use Doctrine\Common\Collections\ArrayCollection;

class TimeCanvasSerie implements \JsonSerializable
{
    /**
     * @var ArrayCollection
     */
    private $points;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $color;

    /**
     * TimeCanvasSerie constructor.
     */
    public function __construct(string $label, string $color)
    {
        $this->points = new ArrayCollection();
        $this->label = $label;
        $this->color = $color;
    }

    /**
     * Add a point
     * @param TimeCanvasPoint $point
     * @return $this
     */
    public function addPoint(TimeCanvasPoint $point) {
        $this->points->add($point);
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPoints(): ArrayCollection
    {
        return $this->points;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'label' => $this->label,
            'backgroundColor' => $this->color,
            'borderColor' => $this->color,
            'fill' => false,
            'data' => $this->points->toArray()
        ];
    }
}