<?php

namespace Memory\Model;

/**
 * Cette classe nous sert à représenter un score.
 * C'est ce qu'on peut appeler un modèle ou une entité.
 */
class Score
{
    /**
     * @param integer|null $id
     * @param integer $time
     */
    public function __construct(private ?int $id, private int $time)
    {
    }

    /**
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return integer
     */
    public function getTime(): int
    {
        return $this->time;
    }
}
