<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Psr\Log\LoggerInterface;
use Illuminate\Contracts\Logging\Log;

/**
 * Class ConditionManager
 * @package App\Services\Activity
 */
class ConditionManager
{
    /**
     * @var Guard
     */
    protected $auth;
    /**
     * @var Log
     */
    protected $dbLogger;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var Version
     */
    protected $version;


    /**
     * @param Version         $version
     * @param Log             $dbLogger
     * @param Guard           $auth
     * @param LoggerInterface $logger
     */
    public function __construct(Version $version, Log $dbLogger, Guard $auth, LoggerInterface $logger)
    {
        $this->auth              = $auth;
        $this->dbLogger          = $dbLogger;
        $this->logger            = $logger;
        $this->iatiConditionRepo = $version->getActivityElement()->getCondition()->getRepository();
    }

    /**
     * updates Activity Date
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        try {
            $this->iatiConditionRepo->update($activityDetails, $activity);
            $this->logger->info(
                'Condition Updated!',
                ['for' => $activity->condition]
            );
            $this->dbLogger->activity(
                "activity.condition_updated",
                [
                    'activity_id'     => $activity->id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error($exception, ['condition' => $activityDetails]);
        }

        return false;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getConditionData($id)
    {
        return $this->iatiConditionRepo->getConditionData($id);
    }
}
