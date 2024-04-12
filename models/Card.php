<?php

namespace Model;

class Card
{
    public string $tableName = "cards";

    public int $id;
    public string $name;
    public string $img;
    public int $isCustom;

    /**
     * @param string $name
     * @param string $img
     */
    public function __construct(string $name, string $img, int $isCustom = 0)
    {
        $this->name = $name;
        $this->img = $img;
        $this->isCustom = $isCustom;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getImg(): string
    {
        return $this->img;
    }

    /**
     * @param string $img
     */
    public function setImg(string $img): void
    {
        $this->img = $img;
    }

    /**
     * @return int
     */
    public function getIsCustom(): int
    {
        return $this->isCustom;
    }

    /**
     * @param int $isCustom
     */
    public function setIsCustom(int $isCustom): void
    {
        $this->isCustom = $isCustom;
    }


}