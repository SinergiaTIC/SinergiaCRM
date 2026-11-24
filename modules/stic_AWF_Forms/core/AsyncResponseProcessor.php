<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'modules/stic_AWF_Forms/core/includes.php';
require_once 'modules/stic_AWF_Forms/core/ResponseProcessingService.php';

class AsyncResponseProcessor
{
    const BATCH_SIZE = 50;
    const TIME_LIMIT = 45;
    const ZOMBIE_TIMEOUT_MINUTES = 15;

    /**
     * Process pending async responses in batch.
     *
     * @return array ['processed' => int, 'errors' => int]
     */
    public static function processBatch(): array
    {
        $GLOBALS['log']->info("Line " . __LINE__ . ": " . __METHOD__ . ": Starting async batch processing");

        $startTime = time();
        $processed = 0;
        $errors = 0;

        // 1. Fetch and lock IDs atomically
        $ids = self::fetchAndLockIds();

        if (empty($ids)) {
            $GLOBALS['log']->debug("Line " . __LINE__ . ": " . __METHOD__ . ": No pending responses to process");
            return ['processed' => 0, 'errors' => 0];
        }

        $GLOBALS['log']->info("Line " . __LINE__ . ": " . __METHOD__ . ": Locked " . count($ids) . " responses for processing");

        // 2. Process each response
        foreach ($ids as $responseId) {
            // Time limit check
            if (time() - $startTime > self::TIME_LIMIT) {
                $GLOBALS['log']->info("Line " . __LINE__ . ": " . __METHOD__ . ": Time limit reached ({$startTime}), exiting batch");
                break;
            }

            // Fault tolerance: wrap each call
            try {
                $result = ResponseProcessingService::processResponse($responseId);
                
                if ($result->isError()) {
                    $errors++;
                } else {
                    $processed++;
                }
            } catch (\Throwable $e) {
                $GLOBALS['log']->error("Line " . __LINE__ . ": " . __METHOD__ . ": Error processing response {$responseId}: " . $e->getMessage());
                
                // Mark as error
                self::markAsError($responseId, $e->getMessage());
                $errors++;
            }
        }

        $GLOBALS['log']->info("Line " . __LINE__ . ": " . __METHOD__ . ": Batch complete. Processed: {$processed}, Errors: {$errors}");

        return ['processed' => $processed, 'errors' => $errors];
    }

    /**
     * Fetch and lock pending response IDs atomically.
     * Uses "Fetch first, try lock individually" pattern to prevent race conditions.
     *
     * @return array Locked response IDs
     */
    private static function fetchAndLockIds(): array
    {
        $db = DBManagerFactory::getInstance();

        // 0. RELEASE ZOMBIES: Reset records stuck in 'processing' for >15 minutes
        $sqlRelease = "UPDATE stic_awf_responses 
                        SET status = 'pending' 
                        WHERE status = 'processing' 
                        AND date_modified < DATE_SUB(NOW(), INTERVAL " . self::ZOMBIE_TIMEOUT_MINUTES . " MINUTE)";
        $db->query($sqlRelease);

        // 1. FETCH CANDIDATES (pending only, from async forms)
        $sqlFetch = "SELECT r.id 
                     FROM stic_awf_responses r
                     INNER JOIN stic_awf_forms f ON r.stic_awf_forms_id = f.id
                     WHERE r.status = 'pending' 
                     AND r.deleted = 0 
                     AND f.deleted = 0
                     AND f.processing_mode = 'async'
                     ORDER BY r.date_entered ASC 
                     LIMIT " . self::BATCH_SIZE;
        
        $result = $db->query($sqlFetch);

        $lockedIds = [];

        // 2. TRY LOCK ONE BY ONE (True atomicity)
        while ($row = $db->fetchByAssoc($result)) {
            $id = $db->quote($row['id']);

            // UPDATE only succeeds if status is STILL 'pending'
            $sqlUpdate = "UPDATE stic_awf_responses 
                          SET status = 'processing' 
                          WHERE id = '{$id}' AND status = 'pending'";
            $db->query($sqlUpdate);

            // If affected_rows > 0, WE got the lock
            // If affected_rows = 0, another cron beat us
            if ($db->getAffectedRowCount() > 0) {
                $lockedIds[] = $row['id'];
            }
        }

        return $lockedIds;
    }

    /**
     * Mark a response as error.
     *
     * @param string $responseId The response ID
     * @param string $errorMessage The error message
     */
    private static function markAsError(string $responseId, string $errorMessage): void
    {
        $responseBean = BeanFactory::getBean('stic_AWF_Responses', $responseId);
        if ($responseBean && !empty($responseBean->id)) {
            $responseBean->status = 'error';
            $responseBean->execution_log = "[" . date('Y-m-d H:i:s') . " - Async Processing Error]\n" . $errorMessage;
            $responseBean->save();
        }
    }
}
