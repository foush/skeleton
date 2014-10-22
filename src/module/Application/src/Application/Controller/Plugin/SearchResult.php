<?php

namespace Application\Controller\Plugin;

use Application\Service\Search\ResultProviderInterface;

class SearchResult extends Base
{
    public function __invoke(ResultProviderInterface $result)
    {

        /* @var $resultService \Application\Service\Search\Result */
        $resultService = $this->getService('result');

        return $resultService->generatePageResult($result);
    }
}
