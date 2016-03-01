@extends('app')

@section('title', 'Register User')

@section('content')
    <div class="container main-container">
        <div class="row">
        @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.errors')
                <div class="panel-content-heading">User Information</div>
                    <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper user-content-wrapper">
                        <div class="create-form create-user-form">
                            <form class="form-horizontal" role="form" method="POST"
                              action="{{ route('admin.signup-user')}}">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="col-xs-12 col-sm-6">
                                            <label class="control-label">First Name</label>
                                                <input type="text" class="form-control" name="first_name"
                                                       value="{{ old('first_name') }}">
                                        </div>

                                        <div class="col-xs-12 col-sm-6">
                                            <label class="control-label">Last Name</label>
                                                <input type="text" class="form-control" name="last_name"
                                                       value="{{ old('last_name') }}">
                                        </div>

                                        <div class="col-xs-12 col-sm-6">
                                            <label class="control-label">E-Mail Address</label>
                                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                        </div>

                                        <div class="col-xs-12 col-sm-6">
                                            <label class="control-label">Username</label>
                                                <input type="text" class="form-control" name="username"
                                                       value="{{ old('username') }}">
                                        </div>

                                        <div class="col-xs-12 col-sm-6">
                                            <label class="control-label">Password</label>
                                                <input type="password" class="form-control" name="password">
                                        </div>

                                        <div class="col-xs-12 col-sm-6">
                                            <label class="control-label">Confirm Password</label>
                                                <input type="password" class="form-control" name="password_confirmation">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default panel-user">
                                    <div class="panel-heading">User permission</div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <div class="checkbox">
                                                <label><input type="checkbox" value="add_activity" name=user_permission[add] class="field1" @if(old('user_permission.add')) checked="checked" @endif >Add</label>
                                            </div>
                                            <div class="checkbox">
                                                <label><input type="checkbox" value="edit_activity" name=user_permission[edit] class="field1" @if(old('user_permission.edit'))) checked="checked" @endif >Edit</label>
                                            </div>
                                            <div class="checkbox">
                                                <label><input type="checkbox" value="delete_activity" name=user_permission[delete] class="field1" @if(old('user_permission.delete'))) checked="checked" @endif >Delete</label>
                                            </div>
                                            <div class="checkbox">
                                                <label><input type="checkbox" value="publish_activity" name=user_permission[publish] class="field1" @if(old('user_permission.publish'))) checked="checked" @endif >Publish</label>
                                            </div>
                                        </div>
                                    </div>
                                    <label><input type="checkbox" class="hidden checkAll"/><span class="btn btn-primary check-all-btn">Check All</span></label>
                                </div>

                                <button type="submit" class="btn btn-primary btn-form btn-submit">
                                    Sign Up
                                </button>

                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection
