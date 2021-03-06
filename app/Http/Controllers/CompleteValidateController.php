<?php namespace App\Http\Controllers;

use App\Services\Activity\ChangeActivityDefaultManager;
use App\Services\FormCreator\Activity\ChangeActivityDefault;
use App\Services\Organization\OrganizationManager;

use App\Services\SettingsManager;
use Illuminate\Session\SessionManager;
use App\Services\Activity\ActivityManager;

class CompleteValidateController extends Controller
{

    /**
     * @var ChangeActivityDefault
     */
    protected $changeActivityDefaultForm;

    /**
     * @var ChangeActivityDefaultManager
     */
    protected $changeActivityDefaultManager;
    /**
     * @var SettingsManager
     */
    private $settingsManager;
    /**
     * @var SessionManager
     */
    private $sessionManager;
    /**
     * @var OrganizationManager
     */
    private $organizationManager;
    /**
     * @var ActivityManager
     */
    private $activityManager;

    /**
     * @param SettingsManager     $settingsManager
     * @param SessionManager      $sessionManager
     * @param OrganizationManager $organizationManager
     * @param ActivityManager     $activityManager
     */
    function __construct(
        SettingsManager $settingsManager,
        SessionManager $sessionManager,
        OrganizationManager $organizationManager,
        ActivityManager $activityManager
    ) {
        $this->middleware('auth.superAdmin');
        $this->settingsManager     = $settingsManager;
        $this->sessionManager      = $sessionManager;
        $this->organizationManager = $organizationManager;
        $this->activityManager     = $activityManager;
    }

    /**
     * it validates activity schema according to version standard
     * @param      $id
     * @param null $version
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function validateActivity($id, $version = null)
    {
        $activityData    = $this->activityManager->getActivityData($id);
        $settings        = $this->settingsManager->getSettings($activityData['organization_id']);
        $transactionData = $this->activityManager->getTransactionData($id);
        $resultData      = $this->activityManager->getResultData($id);
        $organization    = $this->organizationManager->getOrganization($activityData->organization_id);
        $orgElem         = $this->organizationManager->getOrganizationElement();
        $activityElement = $this->activityManager->getActivityElement();

        if ($version == null) {
            $version = config('app.default_version_name');
        }

        return $this->validateCompletedActivity($activityData, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization, $version);
    }

    /**
     * @param $activityData
     * @param $transactionData
     * @param $resultData
     * @param $settings
     * @param $activityElement
     * @param $orgElem
     * @param $organization
     * @param $version
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function validateCompletedActivity($activityData, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization, $version)
    {
        // Enable user error handling
        libxml_use_internal_errors(true);
        $xmlService     = $activityElement->getActivityXmlService();
        $tempXmlContent = $xmlService->generateTemporaryActivityXml($activityData, $transactionData, $resultData, $settings, $activityElement, $orgElem, $organization);
        $xml            = new \DOMDocument();
        $xml->loadXML($tempXmlContent);
        $schemaPath = app_path(sprintf('/Core/%s/XmlSchema/iati-activities-schema.xsd', $version));
        $messages   = [];
        if (!$xml->schemaValidate($schemaPath)) {
            $messages = $this->libxml_display_errors();
        }

        $xmlString = htmlspecialchars($tempXmlContent);
        $xmlString = str_replace(" ", "&nbsp;&nbsp;", $xmlString);
        $xmlLines  = explode("\n", $xmlString);

        return view('validate-schema', compact('messages', 'xmlLines'));
    }

    /**
     * it validates organization schema according to version standard
     * @param      $id
     * @param null $version
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function validateOrganization($id, $version = null)
    {
        $organization     = $this->organizationManager->getOrganization($id);
        $settings         = $this->settingsManager->getSettings($id);
        $organizationData = $this->organizationManager->getOrganizationData($id);
        $orgElem          = $this->organizationManager->getOrganizationElement();

        if ($version == null) {
            $version = config('app.default_version_name');
        }

        return $this->validateCompletedOrganization($organization, $settings, $organizationData, $orgElem, $version);
    }

    public function validateCompletedOrganization($organization, $settings, $organizationData, $orgElem, $version)
    {
        libxml_use_internal_errors(true);

        $xmlService     = $orgElem->getOrgXmlService();
        $tempXmlContent = $xmlService->generateTemporaryOrganizationXml($organization, $organizationData, $settings, $orgElem);
        $xml            = new \DOMDocument();
        $xml->loadXML($tempXmlContent);
        $schemaPath = app_path(sprintf('/Core/%s/XmlSchema/iati-organisations-schema.xsd', $version));
        $messages   = [];
        if (!$xml->schemaValidate($schemaPath)) {
            $messages = $this->libxml_display_errors();
        }

        $xmlString = htmlspecialchars($tempXmlContent);
        $xmlString = str_replace(" ", "&nbsp;&nbsp;", $xmlString);
        $xmlLines  = explode("\n", $xmlString);

        return view('validate-schema', compact('messages', 'xmlLines'));
    }

    /**
     * return xml validation message with type
     * @param $error
     * @return string
     */
    protected function libxml_display_error($error)
    {
        $return = '';
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "Warning $error->code:";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "Error $error->code:";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "Fatal Error $error->code:";
                break;
        }
        $return .= trim($error->message);
        $return .= "in  line no. <a href='#$error->line'><b>$error->line</b></a>";

        return $return;
    }

    /**
     * return xml validation error messages
     * @return array
     */
    protected function libxml_display_errors()
    {
        $errors   = libxml_get_errors();
        $messages = [];
        foreach ($errors as $error) {
            $messages[$error->line] = $this->libxml_display_error($error);
        }
        libxml_clear_errors();

        return $messages;
    }
}