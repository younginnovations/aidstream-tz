<?php

$router->group(
    ['namespace' => 'Complete\Activity'],
    function ($router) {
        $router->resource('activity', 'ActivityController');
        $router->resource('activity.delete', 'ActivityController@destroy');
        $router->resource('activity.reporting-organization', 'ReportingOrganization');
        $router->resource('activity.iati-identifier', 'IatiIdentifierController');
        $router->resource('activity.other-identifier', 'OtherIdentifierController');
        $router->resource('activity.title', 'TitleController');
        $router->resource('activity.description', 'DescriptionController');
        $router->resource('activity.activity-status', 'ActivityStatusController');
        $router->resource('activity.activity-date', 'ActivityDateController');
        $router->resource('activity.contact-info', 'ContactInfoController');
        $router->resource('activity.activity-scope', 'ActivityScopeController');
        $router->resource('activity.participating-organization', 'ParticipatingOrganizationController');
        $router->resource('activity.recipient-country', 'RecipientCountryController');
        $router->resource('activity.recipient-region', 'RecipientRegionController');
        $router->resource('activity.sector', 'SectorController');
        $router->resource('activity.country-budget-items', 'CountryBudgetItemController');
        $router->resource('activity.location', 'LocationController');
        $router->resource('activity.budget', 'BudgetController');
        $router->resource('activity.policy-marker', 'PolicyMarkerController');
        $router->resource('activity.collaboration-type', 'CollaborationTypeController');
        $router->resource('activity.default-flow-type', 'DefaultFlowTypeController');
        $router->resource('activity.default-finance-type', 'DefaultFinanceTypeController');
        $router->resource('activity.default-aid-type', 'DefaultAidTypeController');
        $router->resource('activity.default-tied-status', 'DefaultTiedStatusController');
        $router->resource('activity.capital-spend', 'CapitalSpendController');
        $router->resource('activity.result', 'ResultController');
        $router->resource('activity.result.delete', 'ResultController@destroy');
        $router->resource('activity.condition', 'ConditionController');
        $router->resource('activity.planned-disbursement', 'PlannedDisbursementController');
        $router->resource('activity.document-link', 'DocumentLinkController');
        $router->resource('activity.related-activity', 'RelatedActivityController');
        $router->resource('activity.transaction', 'TransactionController');
        $router->resource('activity.transaction.delete', 'TransactionController@destroy');
        $router->resource('activity.transaction-upload', 'TransactionUploadController');
        $router->resource('activity-upload', 'ActivityUploadController');
        $router->resource('activity.legacy-data', 'LegacyDataController');
        $router->resource('activity.humanitarian-scope', 'HumanitarianScopeController');
        $router->post('activity/{id}/update-status', 'ActivityController@updateStatus');
        $router->get(
            'delete-published-file/{id}',
            [
                'as'   => 'delete-published-file',
                'uses' => 'ActivityController@deletePublishedFile'
            ]
        );
        $router->get(
            'change-activity-default/{id}',
            [
                'as'   => 'change-activity-default',
                'uses' => 'ActivityController@changeActivityDefault'
            ]
        );
        $router->put(
            'update-activity-default/{id}',
            [
                'as'   => 'update-activity-default',
                'uses' => 'ActivityController@updateActivityDefault'
            ]
        );
        $router->resource('activity.humanitarian-scope', 'HumanitarianScopeController');
        $router->get(
            'activity/duplicate/{id}',
            [
                'as'   => 'activity.duplicate',
                'uses' => 'ActivityController@duplicateActivity'
            ]
        );
        $router->post(
            'activity/duplicate/{id}',
            [
                'as'   => 'activity.duplicate',
                'uses' => 'ActivityController@duplicateActivityAction'
            ]
        );
        $router->post(
            'publish/activity',
            [
                'as'   => 'activity.bulk-publish',
                'uses' => 'ActivityController@activityBulkPublishToRegistry'
            ]
        );
    }
);
