@extends('tz.base.sidebar')

@section('title', 'Project')

@inject('getCode', 'App\Helpers\GetCodeName')

@section('content')
    <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
        @include('includes.response')
        <div class="element-panel-heading">
            <div>
                <span>
                    {{ $project->title ? $project->title[0]['narrative'] : 'No Title' }}
                    <a href="{{ route('project.edit', $project->id) }}" class="edit pull-right">Edit</a>
                </span>
                <div class="element-panel-heading-info">
                    <span>{{ $project->identifier['activity_identifier'] }}</span>
                    <span class="last-updated-date">Last Updated on: {{ changeTimeZone($project['updated_at'], 'M d, Y H:i') }}</span>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper fullwidth-wrapper">
            <div class="activity-status activity-status-{{ $statusLabel[$activityWorkflow] }}">
                <ol>
                    @foreach($statusLabel as $key => $value)
                        @if($key == $activityWorkflow)
                            <li class="active"><span>{{ $value }}</span></li>
                        @else
                            <li><span>{{ $value }}</span></li>
                        @endif
                    @endforeach
                </ol>
                @include('tz.project.partials.workflow')
            </div>

            <a href="{{ route('change-project-defaults', $id) }}" class="pull-right">
                <span class="glyphicon glyphicon-triangle-left"></span>Override Project Default Values
            </a>
            <div class="panel panel-default panel-element-detail element-show">

                <div class="activity-element-wrapper">
                    <div class="activity-element-list">
                        <div class="activity-element-label">
                            Project Identifier:
                        </div>
                        <div class="activity-element-info">
                            {{$project->identifier['iati_identifier_text']}}
                        </div>
                    </div>
                </div>

                <div class="activity-element-wrapper">
                    <div class="activity-element-list">
                        <div class="activity-element-label">
                            Project Title:
                        </div>
                        <div class="activity-element-info">
                            {{$project->title[0]['narrative']}}
                        </div>
                    </div>
                </div>

                <div class="activity-element-wrapper">
                    <div class="title">
                        Description
                    </div>
                    @foreach ($project->description as $description)
                        @if(getVal($description, ['type']) == 1)
                            <div class="activity-element-list">
                                <div class="activity-element-label">
                                    General Description:
                                </div>
                                <div class="activity-element-info">
                                    {{$description['narrative'][0]['narrative']}}
                                </div>
                            </div>
                        @endif

                        @if(getVal($description, ['type']) == 2)
                            <div class="activity-element-list">
                                <div class="activity-element-label">
                                    Objectives:
                                </div>
                                <div class="activity-element-info">
                                    {{$description['narrative'][0]['narrative']}}
                                </div>
                            </div>
                        @endif

                        @if(getVal($description, ['type']) == 3)
                            <div class="activity-element-list">
                                <div class="activity-element-label">
                                    Target Groups:
                                </div>
                                <div class="activity-element-info">
                                    {{$description['narrative'][0]['narrative']}}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="activity-element-wrapper">
                    <div class="activity-element-list">
                        <div class="activity-element-label">
                            Project Status:
                        </div>
                        <div class="activity-element-info">
                            {{ $getCode->getCodeListName('Activity','ActivityStatus', $project->activity_status) }}
                        </div>
                    </div>
                </div>

                <div class="activity-element-wrapper">
                    <div class="activity-element-list">
                        <div class="activity-element-label">
                            Sector:
                        </div>
                        <div class="activity-element-info">
                            {{ $getCode->getCodeListName('Activity','SectorCategory', getVal($project->sector, [0, 'sector_category_code'])) }}
                        </div>
                    </div>
                </div>

                @foreach ($project->activity_date as $date)
                    @if(getVal($date, ['type']) == 2)
                        <div class="activity-element-wrapper">
                            <div class="activity-element-list">
                                <div class="activity-element-label">
                                    Start Date:
                                </div>
                                <div class="activity-element-info">
                                    {{$date['date']}}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(getVal($date, ['type']) == 4)
                        <div class="activity-element-wrapper">
                            <div class="activity-element-list">
                                <div class="activity-element-label">
                                    End Date:
                                </div>
                                <div class="activity-element-info">
                                    {{ getVal($date, ['date']) }}
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                <div class="activity-element-wrapper">
                    <div class="title">
                        Location
                    </div>
                    <div class="activity-element-list">
                        <div class="activity-element-label">
                            Region
                        </div>
                        @foreach ($project->location as $location)
                            @foreach (getVal($location, ['administrative'], []) as $value)
                                @if ($value['level'] == 1)
                                    <div class="activity-element-info">
                                        {{ $value['code'] }}
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                </div>

                <div class="activity-element-wrapper">
                    <div class="activity-element-list">
                        <div class="activity-element-label">
                            District
                        </div>
                        @foreach ($project->location as $location)
                            @foreach (getVal($location, ['administrative'], []) as $value)
                                @if ($value['level'] == 2)
                                    <div class="activity-element-info">
                                        {{ getVal($value, ['code']) }}
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                </div>

                @if ($project->participating_organization)
                    <div class="activity-element-wrapper">
                        <div class="title">
                            Participating Organization
                        </div>
                        @foreach ($project->participating_organization as $participatingOrganization)
                            @if(getVal($participatingOrganization, ['narrative', 0, 'narrative']) && getVal($participatingOrganization, ['organization_role']) == "1")
                                <div class="activity-element-list">
                                    <div class="activity-element-label">
                                        Funding Organization Name:
                                    </div>
                                    <div class="activity-element-info">
                                        {{ getVal($participatingOrganization, ['narrative', 0, 'narrative']) }}
                                    </div>
                                </div>
                                <div class="activity-element-list">
                                    <div class="activity-element-label">
                                        Funding Organization Type:
                                    </div>
                                    <div class="activity-element-info">
                                        {{$getCode->getCodeListName('Activity','OrganisationType', getVal($participatingOrganization, ['organization_type']))}}
                                    </div>
                                </div>
                            @endif

                            @if(getVal($participatingOrganization, ['narrative', 0, 'narrative']) && getVal($participatingOrganization, ['organization_role']) == 4)
                                <div class="activity-element-list">
                                    <div class="activity-element-label">
                                        Implementing Organization Name:
                                    </div>
                                    <div class="activity-element-info">
                                        {{ getVal($participatingOrganization, ['narrative', 0, 'narrative']) }}
                                    </div>
                                </div>
                                <div class="activity-element-list">
                                    <div class="activity-element-label">
                                        Implementing Organization Type:
                                    </div>
                                    <div class="activity-element-info">
                                        {{$getCode->getCodeListName('Activity','OrganisationType', getVal($participatingOrganization, ['organization_type']))}}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                <div class="activity-element-wrapper">
                    <div class="activity-element-list">
                        <div class="activity-element-label">
                            Recipient Country:
                        </div>
                        <div class="activity-element-info">
                            {{$getCode->getCodeListName('Organization','Country', $project->recipient_country[0]['country_code'])}}
                        </div>
                    </div>
                </div>

                @if ($project->resultDocuments())
                    <div class="activity-element-wrapper">
                        <div class="title">
                            Results/Outcomes Documents
                        </div>
                        <div class="activity-element-list">
                            <div class="activity-element-label">
                                Title:
                            </div>
                            <div class="activity-element-info">
                                {{ getVal($project->resultDocuments(), ['document_link', 'title', 0, 'narrative', 0, 'narrative']) }}
                            </div>
                        </div>

                        <div class="activity-element-list">
                            <div class="activity-element-label">
                                Url:
                            </div>
                            <div class="activity-element-info">
                                <a href="{{ getVal($project->resultDocuments(), ['document_link', 'url']) }}">{{ getVal($project->resultDocuments(), ['document_link', 'url']) }}</a>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($project->annualReports())
                    <div class="activity-element-wrapper">
                        <div class="title">
                            Annual Reports
                        </div>
                        <div class="activity-element-list">
                            <div class="activity-element-label">
                                Title:
                            </div>
                            <div class="activity-element-info">
                                {{ getVal($project->annualReports(), ['document_link', 'title', 0, 'narrative', 0, 'narrative']) }}
                            </div>
                        </div>
                        <div class="activity-element-list">
                            <div class="activity-element-label">
                                Url:
                            </div>
                            <div class="activity-element-info">
                                <a href="{{ getVal($project->annualReports(), ['document_link', 'url']) }}">{{ getVal($project->annualReports(), ['document_link', 'url']) }}</a>
                            </div>
                        </div>
                    </div>
                @endif

                @include('tz.project.partials.add-budget')

                <div class="activity-element-wrapper">
                    @if(count($disbursement) > 0)
                        <div class="activity-element-label">
                            Disbursement
                        </div>
                        <a href="{{url(sprintf('project/%s/transaction/%s/edit', $project->id, 3))}}"
                           class="edit-element">
                            <span>Edit Disbursement</span>
                        </a>
                        <div>
                            {!! Form::open(['method' => 'POST', 'route' => ['transaction.destroy', $project->id, 3]]) !!}
                            {!! Form::submit('Delete', ['class' => 'pull-left delete-transaction']) !!}
                            {!! Form::close() !!}
                        </div>
                        @foreach($disbursement as $data)
                            <div class="activity-element-info">
                                <li>{{$data['value'][0]['amount']}}, {{ $getCode->getCodeListName('Organization','Currency', $data['value'][0]['currency']) }}</li>
                                <div class="toggle-btn">
                                    <span class="show-more-info">Show more info</span>
                                    <span class="hide-more-info hidden">Hide more info</span>
                                </div>
                                <div class="more-info hidden">
                                    <div class="element-info">
                                        <div class="activity-element-label">
                                            Internal Ref:
                                        </div>
                                        <div class="activity-element-info">
                                            {{$data['reference']}}
                                        </div>
                                    </div>
                                    <div class="element-info">
                                        <div class="activity-element-label">
                                            Transaction Value:
                                        </div>
                                        <div class="activity-element-info">
                                            {{$data['value'][0]['amount']}}
                                        </div>
                                    </div>
                                    <div class="element-info">
                                        <div class="activity-element-label">
                                            Transaction Date:
                                        </div>
                                        <div class="activity-element-info">
                                            {{$data['transaction_date'][0]['date']}}
                                        </div>
                                    </div>
                                    <div class="element-info">
                                        <div class="activity-element-label">
                                            Description
                                        </div>
                                        <div class="activity-element-info">
                                            {{$data['description'][0]['narrative'][0]['narrative']}}
                                        </div>
                                    </div>
                                    <div class="element-info">
                                        <div class="activity-element-label">
                                            Receiver Organization
                                        </div>
                                        <div class="activity-element-info">
                                            {{$data['provider_organization'][0]['narrative'][0]['narrative']}}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    @else
                        <div class="activity-element-list">
                            <div class="title">Incoming Fund</div>
                            <a href="{{ url(sprintf('project/%s/transaction/%s/create', $project->id,3)) }}"
                               class="add-more"><span>Add Disbursement</span></a>
                        </div>
                    @endif
                </div>
                <div class="activity-element-wrapper">
                    @if(count($expenditure) > 0)
                        <div class="activity-element-label">
                            Expenditure
                        </div>
                        <a href="{{url(sprintf('project/%s/transaction/%s/edit', $project->id, 4))}}"
                           class="edit-element">
                                <span>
                                    Edit Expenditure
                                </span>
                        </a>
                        <div>
                            {!! Form::open(['method' => 'POST', 'route' => ['transaction.destroy', $project->id, 4]]) !!}
                            {!! Form::submit('Delete', ['class' => 'pull-left delete-transaction']) !!}
                            {!! Form::close() !!}
                        </div>
                        @foreach($expenditure as $data)
                            <div class="activity-element-info">
                                <li>{{$data['value'][0]['amount']}}, {{ $getCode->getCodeListName('Organization','Currency', $data['value'][0]['currency']) }}</li>
                                <div class="toggle-btn">
                                    <span class="show-more-info">Show more info</span>
                                    <span class="hide-more-info hidden">Hide more info</span>
                                </div>
                                <div class="more-info hidden">
                                    <div class="element-info">
                                        <div class="activity-element-label">
                                            Internal Ref:
                                        </div>
                                        <div class="activity-element-info">
                                            {{$data['reference']}}
                                        </div>
                                    </div>
                                    <div class="element-info">
                                        <div class="activity-element-label">
                                            Transaction Value:
                                        </div>
                                        <div class="activity-element-info">
                                            {{$data['value'][0]['amount']}}
                                        </div>
                                    </div>
                                    <div class="element-info">
                                        <div class="activity-element-label">
                                            Transaction Date:
                                        </div>
                                        <div class="activity-element-info">
                                            {{$data['transaction_date'][0]['date']}}
                                        </div>
                                    </div>
                                    <div class="element-info">
                                        <div class="activity-element-label">
                                            Description
                                        </div>
                                        <div class="activity-element-info">
                                            {{$data['description'][0]['narrative'][0]['narrative']}}
                                        </div>
                                    </div>
                                    <div class="element-info">
                                        <div class="activity-element-label">
                                            Receiver Organization
                                        </div>
                                        <div class="activity-element-info">
                                            {{$data['provider_organization'][0]['narrative'][0]['narrative']}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="activity-element-list">
                            <div class="title">Expenditure</div>
                            <a href="{{ url(sprintf('project/%s/transaction/%s/create', $project->id,4)) }}"
                               class="add-more"><span>Add Expenditure</span></a>
                        </div>
                    @endif
                </div>
                <div class="activity-element-wrapper">
                    @if(count($incomingFund) > 0)
                        <div class="activity-element-label">
                            Incoming Fund
                        </div>
                        <a href="{{url(sprintf('project/%s/transaction/%s/edit', $project->id, 1))}}"
                           class="edit-element"><span>Edit Incoming Fund</span></a>
                        <div>
                            {!! Form::open(['method' => 'POST', 'route' => ['transaction.destroy', $project->id, 1]]) !!}
                            {!! Form::submit('Delete', ['class' => 'pull-left delete-transaction']) !!}
                            {!! Form::close() !!}
                        </div>
                        @foreach($incomingFund as $data)
                            <div class="activity-element-info">
                                <li>{{$data['value'][0]['amount']}}, {{ $getCode->getCodeListName('Organization','Currency', $data['value'][0]['currency']) }}</li>
                                <div class="toggle-btn">
                                    <span class="show-more-info">Show more info</span>
                                    <span class="hide-more-info hidden">Hide more info</span>
                                </div>
                                <div class="more-info hidden">
                                    <div class="element-info">
                                        <div class="activity-element-label">
                                            Internal Ref:
                                        </div>
                                        <div class="activity-element-info">
                                            {{$data['reference']}}
                                        </div>
                                    </div>
                                    <div class="element-info">
                                        <div class="activity-element-label">
                                            Transaction Value:
                                        </div>
                                        <div class="activity-element-info">
                                            {{$data['value'][0]['amount']}}
                                        </div>
                                    </div>
                                    <div class="element-info">
                                        <div class="activity-element-label">
                                            Transaction Date:
                                        </div>
                                        <div class="activity-element-info">
                                            {{$data['transaction_date'][0]['date']}}
                                        </div>
                                    </div>
                                    <div class="element-info">
                                        <div class="activity-element-label">
                                            Description
                                        </div>
                                        <div class="activity-element-info">
                                            {{$data['description'][0]['narrative'][0]['narrative']}}
                                        </div>
                                    </div>
                                    <div class="element-info">
                                        <div class="activity-element-label">
                                            Receiver Organization
                                        </div>
                                        <div class="activity-element-info">
                                            {{$data['provider_organization'][0]['narrative'][0]['narrative']}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="activity-element-list">
                            <div class="title">Incoming Fund</div>
                            <a href="{{ url(sprintf('project/%s/transaction/%s/create', $project->id,1)) }}"
                               class="add-more"><span>Add Incoming Fund</span></a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
