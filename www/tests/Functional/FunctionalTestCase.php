<?php
namespace Tests\Functional;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class FunctionalTestCase extends TestCase
{
    protected App $app;

    protected Connection $dbConnection;

    protected function setUp(): void
    {
        $this->initApp();

        $this->dbConnection = $this->app->getContainer()->get(Connection::class);
        $this->dbConnection->beginTransaction();
    }

    protected function initApp(): void
    {
        // Use the application settings
        $settings = require __DIR__ . '/../../src/Infrastructure/System/settings.php';

        $container = new \Slim\Container($settings);

        // Instantiate the application
        $app = new App($container);

        // Set up Dependencies
        require __DIR__ . '/../../src/Infrastructure/System/dependencies.php';

        // Register routes
        require __DIR__ . '/../../src/Infrastructure/System/routes.php';
        $this->app = $app;
    }

    /**
     * Process the application given a request method and URI
     * Not Real request http
     *
     * @param string $requestMethod the request method (e.g. GET, POST, etc.)
     * @param string $requestUri the request URI
     * @param array|object|null $requestData the request data
     * @return ResponseInterface
     */
    protected function request(string $requestMethod, string $requestUri, $requestData = null): ResponseInterface
    {
        // Create a mock environment for testing with
        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => $requestMethod,
                'REQUEST_URI' => $requestUri
            ]
        );

        $request = Request::createFromEnvironment($environment);

        if (isset($requestData['headers'])) {
            foreach ($requestData['headers'] as $header => $value) {
                $request = $request->withHeader($header, $value);
            }
        }

        if (isset($requestData['body'])) {
            $request = $request->withParsedBody($requestData['body']);
        }

        if (isset($requestData['files'])) {
            $request = $request->withUploadedFiles($requestData['files']);
        }

        $response = new Response();
        $response = $this->app->process($request, $response);

        return $response;
    }

    public function tearDown(): void
    {
        if ($this->dbConnection->isTransactionActive()) {
            $this->dbConnection->rollBack();
        }
        parent::tearDown();
    }
}
