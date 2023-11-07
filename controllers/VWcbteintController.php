<?php

namespace PHPMaker2024\Subastas2024;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PHPMaker2024\Subastas2024\Attributes\Delete;
use PHPMaker2024\Subastas2024\Attributes\Get;
use PHPMaker2024\Subastas2024\Attributes\Map;
use PHPMaker2024\Subastas2024\Attributes\Options;
use PHPMaker2024\Subastas2024\Attributes\Patch;
use PHPMaker2024\Subastas2024\Attributes\Post;
use PHPMaker2024\Subastas2024\Attributes\Put;

/**
 * v_wcbteint controller
 */
class VWcbteintController extends ControllerBase
{
    // custom
    #[Map(["GET", "POST", "OPTIONS"], "/VWcbteint[/{params:.*}]", [PermissionMiddleware::class], "custom.v_wcbteint")]
    public function custom(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "VWcbteint");
    }
}
