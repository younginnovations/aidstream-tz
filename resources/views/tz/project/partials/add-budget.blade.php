<div class="activity-element-wrapper">
    @if ($project->budget)
        <a href="{{ route('project.edit-budget', $project->id) }}"
           class="edit-element">
            <span>Edit Budget</span>
        </a>
        <div>
            {!! Form::open(['method' => 'POST', 'route' => ['project.budget.destroy', $project->id]]) !!}
            {!! Form::submit('Delete', ['class' => 'pull-left delete-transaction']) !!}
            {!! Form::close() !!}
        </div>
        @foreach ($project->budget as $budget)
            <div class="activity-element-list">
                <div class="activity-element-label">
                    Budget
                </div>
                <div class="activity-element-info">
                    {{ getVal($budget, ['value', 0, 'amount']) }} {{ getVal($budget, ['value', 0, 'currency']) }} [{{ getVal($budget, ['period_start', 0, 'date']) }} - {{ getVal($budget, ['period_end', 0, 'date']) }}]
                </div>
            </div>
        @endforeach
    @else
        <div class="activity-element-list">
            <div class="title">
                Budget
            </div>
            <a href="{{ route('project.add-budget', $project->id) }}"
               class="add-more"><span>Add Budget</span></a>
        </div>
    @endif
</div>

