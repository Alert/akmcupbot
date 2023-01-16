<?php
declare(strict_types=1);

namespace App\Service;

use App\Repository\DynamicParamRepository;

/**
 * Dynamic param service
 */
class DynamicParamService
{
    /**
     * Repo
     *
     * @var DynamicParamRepository
     */
    private DynamicParamRepository $repo;

    /**
     * Cache
     *
     * @var array
     */
    private array $cache = [];

    /**
     * Construct
     *
     * @param DynamicParamRepository $dynamicParamRepository
     */
    public function __construct(DynamicParamRepository $dynamicParamRepository)
    {
        $this->repo = $dynamicParamRepository;
    }

    public function getValue(string $name, ?string $default = null): ?string
    {
        if (!$this->cache) {
            $params = $this->repo->findAll();
            foreach ($params as $param) {
                $this->cache[$param->getName()] = $param->getValue();
            }
        }

        return $this->cache[$name] ?: $default;
    }

}
