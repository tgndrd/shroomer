<?php

declare(strict_types=1);

namespace App\Model;

use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(normalizationContext: ['read'])]
readonly class Cost
{
    #[Groups('read')]
    private int $resourceFlora;

    #[Groups('read')]
    private int $resourceFauna;

    #[Groups('read')]
    private int $resourceEntomofauna;

    /**
     * @param int $resourceFlora
     * @param int $resourceFauna
     * @param int $resourceEntomofauna
     */
    public function __construct(
        int $resourceFlora,
        int $resourceFauna,
        int $resourceEntomofauna
    )
    {
        $this->resourceEntomofauna = $resourceEntomofauna;
        $this->resourceFauna = $resourceFauna;
        $this->resourceFlora = $resourceFlora;
    }

    /**
     * @return int
     */
    public function getResourceFlora(): int
    {
        return $this->resourceFlora;
    }

    /**
     * @return int
     */
    public function getResourceFauna(): int
    {
        return $this->resourceFauna;
    }

    /**
     * @return int
     */
    public function getResourceEntomofauna(): int
    {
        return $this->resourceEntomofauna;
    }
}
