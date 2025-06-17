<?php

declare(strict_types=1);

namespace Yakovlef\TelegrafMetricsBundle\Collector;

use Exception;
use InfluxDB2\Client;
use InfluxDB2\Point;
use InfluxDB2\UdpWriter;
use Throwable;

class MetricsCollector implements MetricsCollectorInterface
{
    private UdpWriter $writer;

    /**
     * @throws Exception
     */
    public function __construct(
        private readonly Client $client,
        private readonly string $namespace
    )
    {
        $this->writer = $this->client->createUdpWriter();
    }

    /**
     * @throws Throwable
     */
    public function collect(string $name, array $fields, array $tags = []): void
    {
        $this->writer->write(
            new Point("{$this->namespace}_$name", $tags, $fields)
        );
    }

    public function __destruct()
    {
        $this->writer->close();
    }
}