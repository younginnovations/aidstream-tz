<?php namespace App\Http\Controllers\Wizard\Activity;

use App\Http\Controllers\Controller;
use App\Services\Organization\OrganizationManager;
use App\Services\Wizard\Activity\ActivityManager;
use App\Services\Wizard\Activity\StepThreeManager;
use App\Services\Wizard\FormCreator\Activity\StepThree as StepThreeForm;
use App\Services\Wizard\RequestManager\Activity\StepThree as StepThreeRequestManager;
use App\Http\Requests\Request;

/**
 * Class StepThreeController
 * @package app\Http\Controllers\Wizard\Activity
 */
class StepThreeController extends Controller
{
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var StepThreeForm
     */
    protected $stepThreeForm;
    /**
     * @var StepThreeManager
     */
    protected $stepThreeManager;
    /**
     * @var OrganizationManager
     */
    protected $organizationManager;

    /**
     * @param OrganizationManager $organizationManager
     * @param StepThreeManager    $stepThreeManager
     * @param StepThreeForm       $stepThreeForm
     * @param ActivityManager     $activityManager
     */
    function __construct(
        OrganizationManager $organizationManager,
        StepThreeManager $stepThreeManager,
        StepThreeForm $stepThreeForm,
        ActivityManager $activityManager
    ) {
        $this->middleware('auth');
        $this->activityManager     = $activityManager;
        $this->stepThreeForm       = $stepThreeForm;
        $this->stepThreeManager    = $stepThreeManager;
        $this->organizationManager = $organizationManager;
        $this->organization_id     = session('org_id');
    }

    public function index($id)
    {
        $organization = $this->organizationManager->getOrganization($this->organization_id);
        if (!isset($organization->reporting_org[0])) {
            $response = ['type' => 'warning', 'code' => ['settings', ['name' => 'activity']]];

            return redirect('/settings')->withResponse($response);
        }

        $this->authorize('edit_activity');
        $activityData            = $this->activityManager->getActivityData($id);
        $iatiIdentifier          = $activityData->identifier;
        $data['activity_status'] = $activityData->activity_status;
        $data['start_date']      = $activityData->activity_date[0]['date'];
        $data['end_date']        = $activityData->activity_date[1]['date'];
        $data['date_type']       = $activityData->activity_date[0]['type'];
        $form                    = $this->stepThreeForm->editForm($data, $id);

        return view(
            'wizard.activity.stepThree.create',
            compact('form', 'iatiIdentifier', 'id')
        );
    }

    public function update($id, Request $request, StepThreeRequestManager $stepThreeRequestManager)
    {
        $activityStatus = $request->activity_status;
        if ($request->date_type == "1") {
            $activityDate = [
                [
                    'date'      => $request->start_date,
                    'type'      => "1",
                    'narrative' => [['narrative' => "", 'language' => 'en']]
                ],
                [
                    'date'      => $request->end_date,
                    'type'      => "3",
                    'narrative' => [['narrative' => "", 'language' => 'en']]
                ]
            ];
        } else {
            $activityDate = [
                [
                    'date'      => $request->start_date,
                    'type'      => "2",
                    'narrative' => [['narrative' => "", 'language' => 'en']]
                ],
                [
                    'date'      => $request->end_date,
                    'type'      => "4",
                    'narrative' => [['narrative' => "", 'language' => 'en']]
                ]
            ];
        }
        $dateStatus   = ['activity_status' => $activityStatus, 'activity_date' => $activityDate];
        $activityData = $this->activityManager->getActivityData($id);
        if ($this->stepThreeManager->update($dateStatus, $activityData)){
            $response = ['type' => 'success', 'code' => ['created', ['name' => 'Step Three Completed and Activity ']]];
            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }

        return redirect()->back();
    }
}
