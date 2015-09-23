@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Organization Data</div>

                    <div class="panel-body">
                        <h3>Adding Recipient Country Budget...</h3>
                        {!! form_start($form) !!}
                        <div class="collection-container"
                             data-prototype="{{ form_row($form->recipientCountryBudget->prototype()) }}">
                            {!! form_row($form->recipientCountryBudget) !!}
                        </div>
                        <button type="button" class="add-to-collection">Add More</button>
                        {!! form_end($form) !!}
                    </div>
                </div>
            </div>

            <div class="col-xs-4">
                @include('includes.menu_org')
            </div>
        </div>
    </div>
@endsection