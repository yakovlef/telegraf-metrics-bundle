<?php

declare(strict_types=1);


namespace Yakovlef\TelegrafMetricsBundle\Collector;

interface MetricsCollectorInterface
{
    public function collect(string $name, array $fields, array $tags = []): void;
}