@extends('admin::layouts.content')

@section('page_title')
    {{ __('delhivery::app.admin.system.delhivery.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('delhivery::app.admin.system.delhivery.title') }}</h1>
            </div>
        </div>

        <div class="page-content">
            <form method="POST" action="{{ route('admin.delhivery.store') }}" @submit.prevent="onSubmit">
                @csrf

                <div class="control-group" :class="[errors.has('active') ? 'has-error' : '']">
                    <label for="active">{{ __('delhivery::app.admin.system.delhivery.status') }}</label>
                    <select class="control" name="active" v-validate="'required'">
                        <option value="1" {{ core()->getConfigData('sales.carriers.delhivery.active') ? 'selected' : '' }}>
                            {{ __('admin::app.configuration.index.sales.payment-methods.status-enable') }}
                        </option>
                        <option value="0" {{ !core()->getConfigData('sales.carriers.delhivery.active') ? 'selected' : '' }}>
                            {{ __('admin::app.configuration.index.sales.payment-methods.status-disable') }}
                        </option>
                    </select>
                    <span class="control-error" v-if="errors.has('active')">@{{ errors.first('active') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('title') ? 'has-error' : '']">
                    <label for="title">{{ __('delhivery::app.admin.system.delhivery.title') }}</label>
                    <input type="text" class="control" name="title" v-validate="'required'" value="{{ old('title') ?: core()->getConfigData('sales.carriers.delhivery.title') }}" data-vv-as="&quot;{{ __('delhivery::app.admin.system.delhivery.title') }}&quot;">
                    <span class="control-error" v-if="errors.has('title')">@{{ errors.first('title') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('api_url') ? 'has-error' : '']">
                    <label for="api_url">{{ __('delhivery::app.admin.system.delhivery.api-url') }}</label>
                    <input type="text" class="control" name="api_url" v-validate="'required|url'" value="{{ old('api_url') ?: core()->getConfigData('sales.carriers.delhivery.api_url') }}" data-vv-as="&quot;{{ __('delhivery::app.admin.system.delhivery.api-url') }}&quot;">
                    <span class="control-error" v-if="errors.has('api_url')">@{{ errors.first('api_url') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('api_token') ? 'has-error' : '']">
                    <label for="api_token">{{ __('delhivery::app.admin.system.delhivery.api-token') }}</label>
                    <input type="text" class="control" name="api_token" v-validate="'required'" value="{{ old('api_token') ?: core()->getConfigData('sales.carriers.delhivery.api_token') }}" data-vv-as="&quot;{{ __('delhivery::app.admin.system.delhivery.api-token') }}&quot;">
                    <span class="control-error" v-if="errors.has('api_token')">@{{ errors.first('api_token') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('client_name') ? 'has-error' : '']">
                    <label for="client_name">{{ __('delhivery::app.admin.system.delhivery.client-name') }}</label>
                    <input type="text" class="control" name="client_name" v-validate="'required'" value="{{ old('client_name') ?: core()->getConfigData('sales.carriers.delhivery.client_name') }}" data-vv-as="&quot;{{ __('delhivery::app.admin.system.delhivery.client-name') }}&quot;">
                    <span class="control-error" v-if="errors.has('client_name')">@{{ errors.first('client_name') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('warehouse_pincode') ? 'has-error' : '']">
                    <label for="warehouse_pincode">{{ __('delhivery::app.admin.system.delhivery.warehouse-pincode') }}</label>
                    <input type="text" class="control" name="warehouse_pincode" v-validate="'required|digits:6'" value="{{ old('warehouse_pincode') ?: core()->getConfigData('sales.carriers.delhivery.warehouse_pincode') }}" data-vv-as="&quot;{{ __('delhivery::app.admin.system.delhivery.warehouse-pincode') }}&quot;">
                    <span class="control-error" v-if="errors.has('warehouse_pincode')">@{{ errors.first('warehouse_pincode') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('account_id') ? 'has-error' : '']">
                    <label for="account_id">{{ __('delhivery::app.admin.system.delhivery.account-id') }}</label>
                    <input type="text" class="control" name="account_id" v-validate="'required'" value="{{ old('account_id') ?: core()->getConfigData('sales.carriers.delhivery.account_id') }}" data-vv-as="&quot;{{ __('delhivery::app.admin.system.delhivery.account-id') }}&quot;">
                    <span class="control-error" v-if="errors.has('account_id')">@{{ errors.first('account_id') }}</span>
                </div>

                <div class="control-group">
                    <label for="debug">{{ __('delhivery::app.admin.system.delhivery.debug') }}</label>
                    <input type="checkbox" class="control" name="debug" {{ core()->getConfigData('sales.carriers.delhivery.debug') ? 'checked' : '' }}>
                </div>

                <button type="submit" class="btn btn-primary">
                    {{ __('admin::app.configuration.save-btn-title') }}
                </button>
            </form>
        </div>
    </div>
@stop