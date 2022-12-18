<?php

declare(strict_types=1);

namespace steinmb;

interface EntityInterface
{
    public function timeStamp(): string;
    public function id(): string;
    public function getSensorType(): string;
    public function getData(): string;
    public function measurement(): string;
}
