<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Handlers\ErrorHandler;
use App\Core\Handlers\ResponseHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Throwable;

class Application
{
    private static ?Application $instance = null;
    private const string CONTAINER_FILE = 'config/containers.php';
    private const string ROUTES_FILE = 'config/routes.php';
    private mixed $container;
    private mixed $routes;
    private Request $request;

    public function __construct(
        private readonly string $projectDir,
        private readonly ErrorHandler $errorHandler,
    ) {
        $this->loadConfigFiles();
        $this->request = Request::createFromGlobals();
    }

    public static function getInstance(): Application
    {
        if (self::$instance === null) {
            self::$instance = new Application(
                __DIR__ . '/../../',
                new ErrorHandler(),
                new ResponseHandler(),
            );
        }

        return self::$instance;
    }

    private function loadConfigFiles(): void
    {
        $this->container = require $this->projectDir . self::CONTAINER_FILE;
        $this->routes = require $this->projectDir . self::ROUTES_FILE;
    }

    public function init(): void {
        $response = $this->getResponseData($this->request);
        $response->send();
    }

    private function getResponseData(Request $request): Response
    {
        $context = new RequestContext();
        $context->fromRequest($this->request);
        $matcher = new UrlMatcher($this->routes, $context);

        try {
            $params = $matcher->match($this->request->getPathInfo());
            $controllerData = $params['_controller'];
            $controllerService = $this->container->get($controllerData[0]);
            $method = $controllerData[1];

            unset($params['_controller'], $params['_route']);

            return call_user_func_array([$controllerService, $method], array_merge([$request], $params));
        } catch (Throwable $e) {
            print $e->getMessage();
            return $this->errorHandler->handleException($e);
        }
    }
}