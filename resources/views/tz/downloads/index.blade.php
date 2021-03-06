@extends('tz.base.sidebar')

@section('title', 'Downloads')

@section('content')
        <div class="col-xs-9 col-lg-9 content-wrapper">
            @include('includes.response')
            <div class="panel panel-default">
                <div class="element-panel-heading">
                    <div>Download Data</div>
                </div>
                <div class="panel-body panel-download">
                    <div class="download-wrapper">
                        <div class="download-block">
                            <div class="download-data-title">Simple</div>
                            <p>Contains full information for each project with one row per project. Multiple
                                values for a column (e.g. multiple transactions, multiple sectors) are separated by
                                ";".
                            </p>
                            <a href="{{route('download.simple')}}" class="btn btn-primary">Download</a>
                        </div>
                        <div class="download-block">
                            <div class="download-data-title">Complete</div>
                            <p>Contains a row for each aid project. Each project contains total figures for each
                                type of transaction. Useful for quick verification of the data.</p>
                            <a href="{{ route('download.complete') }}" class="btn btn-primary">Download</a>
                        </div>
                        <div class="download-block download-transaction-block">
                            <div class="download-data-title">Transactions</div>
                            <p>Contains a detail list of transactions for all projects with currencies, amounts
                                and classifications. A row for each transaction.</p>
                            <a href="{{ route('download.transaction') }}" class="btn btn-primary">Download</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
