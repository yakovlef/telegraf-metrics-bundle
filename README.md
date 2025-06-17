# TelegrafMetricsBundle

Symfony bundle for sending metrics to Telegraf/InfluxDB via UDP protocol.

## Installation

```bash
composer require yakovlef/telegraf-metrics-bundle
```

## Configuration

Add to your `config/bundles.php`:
```php
Yakovlef\TelegrafMetricsBundle\TelegrafMetricsBundle::class => ['all' => true],
```

Configure in `config/packages/telegraf_metrics.yaml`:
```yaml
telegraf_metrics:
    namespace: 'my_app'
    client:
        url: 'http://localhost:8086'
        udpPort: 8089
```

## Usage

### Basic Example

```php
use Yakovlef\TelegrafMetricsBundle\Collector\MetricsCollectorInterface;

class UserController
{
    public function __construct(
        private MetricsCollectorInterface $metricsCollector
    ) {}

    public function register(): Response
    {
        // Your business logic here...

        // Send metrics
        $this->metricsCollector->collect('user_registration', [
            'count' => 1
        ], [
            'source' => 'web', //source for example web, mobile, api
            'country' => 'US' //country of the user
        ], );

        return new JsonResponse(['status' => 'success']);
    }
}
```

### Advanced Examples

```php
// Performance metrics
$this->metricsCollector->collect('api_response', [
    'response_time' => 145.2,
    'memory_usage' => 1024
], [
    'endpoint' => '/api/users',
    'method' => 'GET',
    'status' => '200'
]);

// Business metrics
$this->metricsCollector->collect('order_created', [
    'amount' => 99.99,
    'items_count' => 3
], [
    'payment_method' => 'credit_card',
    'currency' => 'USD'
]);

// Error tracking
$this->metricsCollector->collect('application_error', [
    'count' => 1
], [
    'type' => 'database_connection',
    'severity' => 'critical'
]);
```

## Telegraf Configuration

Configure Telegraf to accept UDP input:

```toml
# /etc/telegraf/telegraf.conf

[[inputs.socket_listener]]
  service_address = "udp://:8089" # This port must match the one in telegraf_metrics.yaml
  data_format = "influx"
```
## Prometheus & Grafana Support

In addition to InfluxDB, you can also use this bundle to send metrics to **Prometheus** via Telegraf.

Telegraf will act as a proxy: it receives metrics via UDP and exposes them to Prometheus via the `/metrics` HTTP endpoint.

### Telegraf Configuration Example

```toml
# Output: expose metrics for Prometheus
[[outputs.prometheus_client]]
  listen = ":9273"
  metric_version = 2
  path = "/metrics"
```

### Prometheus Configuration

```yaml
scrape_configs:
  # Telegraf metrics
  - job_name: 'telegraf'
    static_configs:
      - targets: ['localhost:9273']
    scrape_interval: 10s
    metrics_path: /metrics
```
Ensure that the host and port match the actual Telegraf service in your environment.

## Concepts

- **Measurement**: The metric name (e.g., `api_response`, `user_registration`)
- **Tags**: Key-value pairs for filtering and grouping (e.g., `status`, `country`)
- **Fields**: Actual measured values (e.g., `response_time`, `count`, `amount`)

## Requirements

- PHP 8.1+
- Symfony 6.4+ or 7.0+

## License

MIT