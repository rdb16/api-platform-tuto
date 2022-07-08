<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\Operation;

class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        /** @var PathItem $path */
        foreach ($openApi->getPaths()->getPaths() as $key => $path) {
            # code...
            if ($path->getGet() && $path->getGet()->getSummary() === 'hidden') {
                $openApi->getPaths()->addPath($key, $path->withGet(null));
            }
        }
        #dd($openApi);
        // on ajoute une entrée dans la doc  ;-: le PING
        $openApi->getPaths()->addPath(
            '/ping',
            new PathItem(
                'ping-id',
                'PING',
                null,
                new Operation(
                    'ping-id',
                    [],
                    [],
                    'Répond'
                )
            )
        );


        return $openApi;
    }
}
