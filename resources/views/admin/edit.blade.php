@extends('webed-core::admin._master')

@section('css')

@endsection

@section('js')

@endsection

@section('js-init')

@endsection

@section('content')
    <div class="layout-1columns">
        <div class="column main">
            @php
                $curentTab = Request::get('_tab', 'user_profiles');
            @endphp
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs tab-change-url">
                    <li class="{{ $curentTab === 'user_profiles' ? 'active' : '' }}">
                        <a data-target="#user_profiles"
                           data-toggle="tab"
                           href="{{ Request::url() }}?_tab=user_profiles"
                           aria-expanded="false">User profiles</a>
                    </li>
                    <li class="{{ $curentTab === 'change_password' ? 'active' : '' }}">
                        <a data-target="#change_password"
                           data-toggle="tab"
                           href="{{ Request::url() }}?_tab=change_password"
                           aria-expanded="false">Password</a>
                    </li>
                    @php do_action('tab_nav', 'ecommerce.customers.edit') @endphp
                </ul>
                <div class="tab-content">
                    <div class="tab-pane {{ $curentTab === 'user_profiles' ? 'active' : '' }}" id="user_profiles">
                        {!! Form::open(['class' => 'js-validate-form', 'url' => route('admin::ecommerce.customers.edit.post', ['id' => $object->id])]) !!}
                        {!! Form::hidden('_tab', 'user_profiles') !!}
                        <div class="form-group">
                            <label class="control-label "><b>Display name</b></label>
                            <input type="text" value="{{ $object->display_name or '' }}"
                                   name="display_name"
                                   autocomplete="off"
                                   class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label"><b>Username</b></label>
                            <input type="text" value="{{ $object->username or '' }}"
                                   name="username"
                                   autocomplete="off"
                                   class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label"><b>Email</b></label>
                            <input type="text" value="{{ $object->email or '' }}"
                                   name="email"
                                   autocomplete="off"
                                   class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label "><b>First name</b></label>
                            <input type="text" value="{{ $object->first_name or '' }}"
                                   name="first_name"
                                   autocomplete="off"
                                   class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label"><b>Last name</b></label>
                            <input type="text" value="{{ $object->last_name or '' }}"
                                   name="last_name"
                                   autocomplete="off"
                                   class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label"><b>Phone</b></label>
                            <input type="text" value="{{ $object->phone or '' }}"
                                   name="phone"
                                   autocomplete="off"
                                   class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label"><b>Mobile phone</b></label>
                            <input type="text" value="{{ $object->mobile_phone or '' }}"
                                   name="mobile_phone"
                                   autocomplete="off"
                                   class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label"><b>Sex</b></label>
                            @php
                                $selected = isset($object->sex) ?  $object->sex : 'male';
                            @endphp
                            {!! Form::customRadio('sex', [
                                ['male', 'Male'],
                                ['female', 'Female'],
                                ['other', 'Other'],
                            ], $selected) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label"><b>Status</b></label>
                            <div class="mt-radio-list">
                                <label class="mt-radio mt-radio-outline">
                                    <input type="radio" name="status" value="activated"
                                        {{ (isset($object) && $object->status == 'activated') ? 'checked' : '' }}>
                                    Activated
                                    <span></span>
                                </label>
                                <label class="mt-radio mt-radio-outline">
                                    <input type="radio" name="status" value="disabled"
                                        {{ (!isset($object) || $object->status == 'disabled' || !$object->status) ? 'checked' : '' }}>
                                    Disabled
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label"><b>Birthday</b></label>
                            <input type="text"
                                   value="{{ isset($object->birthday) && $object->birthday ? convert_timestamp_format($object->birthday, 'Y-m-d') : '' }}"
                                   name="birthday"
                                   data-date-format="yyyy-mm-dd"
                                   autocomplete="off"
                                   readonly
                                   class="form-control js-date-picker input-medium"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label"><b>About</b></label>
                            <textarea class="form-control"
                                      name="description"
                                      rows="5">{!! $object->description or '' !!}</textarea>
                        </div>
                        <div class="form-group">
                            {!! Form::selectImageBox('avatar', (isset($object->avatar) ? $object->avatar : '')) !!}
                        </div>
                        <div class="mt10 text-right">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-check"></i> Save
                            </button>
                            <button class="btn btn-success" type="submit"
                                    name="_continue_edit" value="1">
                                <i class="fa fa-check"></i> Save & continue
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="tab-pane {{ $curentTab === 'change_password' ? 'active' : '' }}" id="change_password">
                        {!! Form::open(['class' => 'js-validate-form', 'url' => route('admin::ecommerce.customers.update-password.post', ['id' => $object->id])]) !!}
                        {!! Form::hidden('_tab', 'change_password') !!}
                        <div class="form-group">
                            <label>
                                <b>New password <span class="text-danger">(*)</span></b>
                            </label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-lock"></i>
                                </span>
                                {!! Form::password('password', [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'autocomplete' => 'off',
                                ]) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>
                                <b>Confirmation <span class="text-danger">(*)</span></b>
                            </label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-lock"></i>
                                </span>
                                {!! Form::password('password_confirmation', [
                                    'class' => 'form-control',
                                    'id' => 'password_confirmation',
                                    'autocomplete' => 'off',
                                ]) !!}
                            </div>
                        </div>
                        <div class="mt10 text-right">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-check"></i> Save
                            </button>
                            <button class="btn btn-success" type="submit"
                                    name="_continue_edit" value="1">
                                <i class="fa fa-check"></i> Save & continue
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    @php do_action('tab_content', 'ecommerce.customers.edit') @endphp
                </div>
            </div>
        </div>
    </div>
@endsection
