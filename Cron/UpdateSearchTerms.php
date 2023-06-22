<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Codepeak\SearchTermsCleanup\Cron;

use Psr\Log\LoggerInterface;
use Magento\Search\Model\ResourceModel\Query\CollectionFactory;
use Magento\Search\Model\Query;

class UpdateSearchTerms
{
    protected $logger;

    protected $collectionFactory;

    /**
     * Constructor
     *
     * @param  LoggerInterface  $logger
     * @param  CollectionFactory  $collectionFactory
     */
    public function __construct(LoggerInterface $logger, CollectionFactory $collectionFactory)
    {
        $this->logger = $logger;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        $this->logger->info("Cronjob UpdateSearchTerms is executed.");

        $searchTermsCollection = $this->getSearchTerms();
        $currentPage = 1;
        $lastPage = $searchTermsCollection->getLastPageNumber();
        $idsToInactivate = [];

        // Loop each page and handle it
        while ($currentPage <= $lastPage) {
            foreach ($searchTermsCollection as $term) {
                if (!$this->shouldKeepTerm($term)) {
                    $idsToInactivate[] = $term->getQueryId();
                    $term->setIsActive(0)->save();
                }
            }
            $currentPage++;
            $searchTermsCollection = $this->getSearchTerms($currentPage);
        }

        $this->logger->info('Found ' . count($idsToInactivate) . ' search terms that were inactivated.');

        $this->logger->info("Cronjob UpdateSearchTerms is finished.");
    }

    public function getBadPatterns(): array
    {
        return [
            '<script',
            '<iframe',
            '(SELECT',
            '" OR ',
            "' OR ",
            'onload=',
            'CHR(98)',
            'SELECT+ALL',
            'SELECT ALL',
            'SELECT UNION'
        ];
    }

    protected function shouldKeepTerm(Query $term): bool
    {
        foreach ($this->getBadPatterns() as $pattern) {
            if (stripos($term->getQueryText(), $pattern) !== false) {
                return false;
            }
        }

        return true;
    }

    protected function getSearchTerms(int $currentPage = 1)
    {
        $searchCollection = $this->collectionFactory->create();

        return $searchCollection
            ->addFieldToFilter('is_active', 1)
            ->setPageSize(1000)
            ->setCurPage($currentPage)
            ->load();
    }
}

