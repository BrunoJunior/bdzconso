<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 04/05/18
 * Time: 20:56
 */

namespace App\Tools;


class Tuile
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $info;

    /**
     * @var string
     */
    private $action;

    /**
     * @param string $title
     * @param string $info
     * @return Tuile
     */
    public static function getInstance(string $title, string $info) {
        $tuile = new static();
        $tuile->info = $info;
        $tuile->title = $title;
        return $tuile;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Tuile
     */
    public function setTitle(string $title): Tuile
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getInfo(): string
    {
        return $this->info;
    }

    /**
     * @param string $info
     * @return Tuile
     */
    public function setInfo(string $info): Tuile
    {
        $this->info = $info;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return Tuile
     */
    public function setAction(string $action): Tuile
    {
        $this->action = $action;
        return $this;
    }
}