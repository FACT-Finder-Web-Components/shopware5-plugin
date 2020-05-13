<?php

namespace OmikronFactfinder\Components\Data;

interface ExportEntityInterface
{
    /**
     * Get entity ID
     *
     * @return int
     */
    public function getId();

    /**
     * Convert entity data to associative array
     *
     * @return array
     */
    public function toArray();
}
