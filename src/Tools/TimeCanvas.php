<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 03/05/18
 * Time: 23:31
 */

namespace App\Tools;

class TimeCanvas implements \JsonSerializable
{

    /**
     * @var array
     */
    private $series = [];

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $yScaleLabel;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return TimeCanvas
     */
    public function setName(string $name): TimeCanvas
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param TimeCanvasSerie $serie
     * @return $this
     */
    public function addSerie(TimeCanvasSerie $serie) {
        $this->series[] = $serie;
        return $this;
    }

    /**
     * @return array
     */
    public function getSeries(): array
    {
        return $this->series;
    }

    /**
     * @return string
     */
    public function getYScaleLabel(): string
    {
        return $this->yScaleLabel;
    }

    /**
     * @param string $yScaleLabel
     * @return $this
     */
    public function setYScaleLabel(string $yScaleLabel): TimeCanvas
    {
        $this->yScaleLabel = $yScaleLabel;
        return $this;
    }

    /**
     * @return string
     */
    public function getJson(): string
    {
        return json_encode($this);
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
            'type' => 'line',
            'data' => [
                'labels' => [],
                'datasets' => $this->series
            ],
            'options' => [
                'title' => ['text' => $this->name],
                'maintainAspectRatio' => false,
                'scales' => [
                    'xAxes' => [
                        [
                            'type' => 'time',
                            'gridLines' => ['display' => false],
                            'time' => ['unit' => 'month', 'tooltipFormat' => 'L'],
                            'scaleLabel' => ['display' => false]
                        ]
                    ],
                    'yAxes' => [
                        [
                            'scaleLabel' => ['display' => true, 'labelString' => $this->yScaleLabel]
                        ]
                    ]
                ]
            ],
        ];
    }
}