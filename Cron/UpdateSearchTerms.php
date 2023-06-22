<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Codepeak\SearchTermsCleanup\Cron;

use \Psr\Log\LoggerInterface;

class UpdateSearchTerms
{

    protected $logger;

    /**
     * Constructor
     *
     * @param  LoggerInterface  $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        $this->logger->addInfo("Cronjob UpdateSearchTerms is executed.");

        $this->logger->addInfo("Cronjob UpdateSearchTerms is finished.");
    }
}

