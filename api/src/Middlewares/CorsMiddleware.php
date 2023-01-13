<?php

declare(strict_types = 1);

namespace App\Middlewares;

use Slim\Psr7\Response;
use App\Lib\Misc\Helper;
use App\Enums\StatusCodes;
use App\Enums\RequestMethods;
use App\Interfaces\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CorsMiddleware extends Middleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        //Get the origin domain
        $origin = $request->getHeader('Origin')[0];

        //Check for pre-flight request
        if($request->getMethod() === RequestMethods::HTTP_OPTIONS->value){

            return $this->respondWithData([], StatusCodes::HTTP_200, [
                'Access-Control-Allow-Origin'=> Helper::checkOrigin($origin, $this->settings['cors_domains']),
                'Access-Control-Allow-Headers' => 'X-Requested-With, Content-Type, Accept, Origin, Authorization, Secret',
                'Content-Type' => 'application/json',
                'Cache-Control'=> 'no-store'
            ]);
        }

        return $handler->handle($request);

    }

}