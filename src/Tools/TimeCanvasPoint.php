<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 03/05/18
 * Time: 23:23
 */

namespace App\Tools;


class TimeCanvasPoint implements \JsonSerializable
{
    /**
     * Date point
     * @var \DateTime
     */
    private $date;

    /**
     * Value
     * @var float
     */
    private $value;

    /**
     * @var TimeCanvasSerie
     */
    private $serie;

    /**
     * TimeCanvasPoint constructor.
     * @param \DateTime $date
     * @param float $value
     */
    public function __construct(\DateTime $date, float $value)
    {
        $this->date = clone $date;
        $this->value = $value;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return TimeCanvasPoint
     */
    public function setDate(\DateTime $date): TimeCanvasPoint
    {
        $this->date = clone $date;
        return $this;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param float $value
     * @return TimeCanvasPoint
     */
    public function setValue(float $value): TimeCanvasPoint
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return TimeCanvasSerie
     */
    public function getSerie(): ?TimeCanvasSerie
    {
        return $this->serie;
    }

    /**
     * @param TimeCanvasSerie $serie
     * @return TimeCanvasPoint
     */
    public function setSerie(TimeCanvasSerie $serie): TimeCanvasPoint
    {
        $this->serie = $serie;
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
            'x' => $this->date->format('Y-m-d'),
            'y' => $this->value
        ];
    }
}