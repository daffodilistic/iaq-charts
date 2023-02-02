<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Selective\BasePath\BasePathMiddleware;
use RedBeanPHP\R;
use Slim\Exception\HttpNotFoundException;

require __DIR__ . '/../vendor/autoload.php';

R::setup('mysql:host=mysql;dbname=iaq-charts', 'iaq', 'iaq' );

$app = AppFactory::create();

// Set the base path to run the app in a subdirectory.
// This path is used in urlFor().
$app->add(new BasePathMiddleware($app));
// Add Slim routing middleware
$app->addRoutingMiddleware();
// Parse json, form data and xml
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

// CORS suppport
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("OK!");
    return $response;
});

$app->get('/readings', function (Request $request, Response $response, array $args) {
    // Query DB for readings by date range
    // This is assuming that the dates are in the correct format (e.g. '31/01/2021')
    $query_data = $request->getQueryParams();
    $start = $query_data['start'];
    $end = $query_data['end'];
    $readings = R::find('readings', 'timestamp >= ? AND timestamp <= ?', [$start, $end]);

    $response->getBody()->write(json_encode($readings));
    $response = $response->withHeader('Content-type', 'application/json');
    return $response;
});

$app->post('/api/upload', function (Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    // $html = var_export($data, true);

    // $STDERR = fopen('php://stdout', 'wb');
    // fwrite($STDERR, json_encode($data[0], JSON_PRETTY_PRINT));
    // fclose($STDERR);

    foreach ($data as $record) {
        $readings = R::dispense( 'readings' );

        foreach ($record as $key => $value) {
            if ($key != "") {
                $prop = strtolower($key);
                $prop = str_replace(' ', '_', $prop);

                if ($prop == "timestamp") {
                    $date = DateTimeImmutable::createFromFormat("d/m/y H:i", $value);
                    $value = DateTime::createFromImmutable($date);
                }
                $readings->$prop = $value;
            }
        }

        R::store( $readings );
    }

    $response->getBody()->write("DONE!");
    return $response;
});

/**
 * Catch-all route to serve a 404 Not Found page if none of the routes match
 * NOTE: make sure this route is defined last
 */
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
    throw new HttpNotFoundException($request);
});

$app->run();