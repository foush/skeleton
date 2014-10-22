<?php

namespace Application\Service\Search;

use Application\Service\Base as BaseService;

/**
 * Class SearchResult
 * @package Application\Service\Search
 * Service Key: result
 */
class Result extends BaseService
{
    public function generatePageResult(ResultProviderInterface $provider)
    {
        return array(
            'data' => $provider->getResults(),
            'meta' => array(
                'total' => $provider->getTotal(),
                'limit' => $provider->getLimit(),
                'offset' => $provider->getOffset(),
                'tag' => $provider->getResultTag(),
            ),
        );
    }

    public function __invoke(ResultProviderInterface $provider)
    {
        return $this->generatePageResult($provider);
    }
}
