<?php
namespace App\Services\Activity;

use App\Core\Version;
use App;
use App\Models\Activity\Activity;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log;

class OtherIdentifierManager
{

    protected $repo;
    /**
     * @var Guard
     */
    protected $auth;
    /**
     * @var Log
     */
    protected $log;
    /**
     * @var Version
     */
    protected $version;

    /**
     * @param Version $version
     * @param Log     $log
     * @param Guard   $auth
     */
    function __construct(Version $version, Log $log, Guard $auth)
    {
        $this->repo    = $version->getActivityElement()->getOtherIdentifier()->getRepository();
        $this->auth    = $auth;
        $this->log     = $log;
        $this->version = $version;
    }

    /**
     * @param array    $input
     * @param Activity $activity
     * @return bool
     */
    public function update(array $input, Activity $activity)
    {
        try {
            $this->repo->update($input, $activity);
            $this->log->info(
                'Activity Other Identifier  Updated',
                ['for ' => $activity['other_identifier']]
            );
            $this->log->activity(
                "activity.other_identifier_updated",
                ['name' => $this->auth->user()->organization->name]
            );

            return true;
        } catch (Exception $exception) {

            $this->log->error(
                sprintf('Other Identifier could not be updated due to %s', $exception->getMessage()),
                [
                    'OtherIdentifier' => $input,
                    'trace'           => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $id
     * @return model
     */
    public function getOtherIdentifierData($id)
    {
        return $this->repo->getOtherIdentifierData($id);
    }

    /**
     * @param $id
     * @return model
     */
    public function getActivityData($id)
    {
        return $this->repo->getActivityData($id);

    }
}