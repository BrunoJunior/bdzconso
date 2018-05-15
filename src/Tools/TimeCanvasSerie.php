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
     * @var ArrayCollection|TimeCanvasPoint[]
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
     * @var TimeCanvas
     */
    private $canvas;

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
     * @param string
     * @return $this
     */
    public function addPoint(TimeCanvasPoint $point): TimeCanvasSerie
    {
        $this->points->add($point->setSerie($this));
        return $this;
    }

    /**
     * @return ArrayCollection|TimeCanvasPoint[]
     */
    public function getPoints(): ArrayCollection
    {
        return $this->points;
    }

    /**
     * @return TimeCanvas
     */
    public function getCanvas(): ?TimeCanvas
    {
        return $this->canvas;
    }

    /**
     * @param TimeCanvas $canvas
     * @return TimeCanvasSerie
     */
    public function setCanvas(TimeCanvas $canvas): TimeCanvasSerie
    {
        $this->canvas = $canvas;
        return $this;
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