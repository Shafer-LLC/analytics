@section('site_title', formatTitle([__('Edit'), __('Plan'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('admin.dashboard'), 'title' => __('Admin')],
    ['url' => route('admin.plans'), 'title' => __('Plans')],
    ['title' => __('Edit')],
]])

<h1 class="h2 mb-3 d-inline-block">{{ __('Edit') }}</h1>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">
                    {{ __('Plan') }}
                    @if($plan->trashed())
                        <span class="badge badge-danger">{{ __('Disabled') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-auto">
                <div class="form-row">
                    <div class="col">
                        @include('admin.plans.partials.menu')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        @if($plan->trashed())
            <div class="alert alert-danger" role="alert">
                {{ __(':name is disabled.', ['name' => $plan->name]) }}
            </div>
        @endif

        <form action="{{ route('admin.plans.edit', $plan->id) }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="i-name">{{ __('Name') }}</label>
                <input type="text" name="name" id="i-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') ?? $plan->name }}">
                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-description">{{ __('Description') }}</label>
                <input type="text" name="description" id="i-description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" value="{{ old('description') ?? $plan->description }}">
                @if ($errors->has('description'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                @endif
            </div>

            @if(!$plan->isDefault())
                <div class="form-group">
                    <label for="i-trial-days">{{ __('Trial days') }}</label>
                    <input type="number" name="trial_days" id="i-trial-days" class="form-control{{ $errors->has('trial_days') ? ' is-invalid' : '' }}" value="{{ old('trial_days') ?? $plan->trial_days }}">
                    @if ($errors->has('trial_days'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('trial_days') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="i-currency">{{ __('Currency') }}</label>
                    <select name="currency" id="i-currency" class="custom-select{{ $errors->has('currency') ? ' is-invalid' : '' }}">
                        @foreach(config('currencies.all') as $key => $value)
                            <option value="{{ $key }}" @if((old('currency') !== null && old('currency') == $key) || ($plan->currency == $key && old('currency') == null)) selected @endif>{{ $key }} - {{ $value }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('currency'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('currency') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="row mx-n2">
                    <div class="col-12 col-lg-6 px-2">
                        <div class="form-group">
                            <label for="i-amount-month">{{ __('Monthly amount') }}</label>
                            <input type="text" name="amount_month" id="i-amount-month" class="form-control{{ $errors->has('amount_month') ? ' is-invalid' : '' }}" value="{{ old('amount_month') ?? $plan->amount_month }}">
                            @if ($errors->has('amount_month'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('amount_month') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 px-2">
                        <div class="form-group">
                            <label for="i-amount-year">{{ __('Yearly amount') }}</label>
                            <input type="text" name="amount_year" id="i-amount-year" class="form-control{{ $errors->has('amount_year') ? ' is-invalid' : '' }}" value="{{ old('amount_year') ?? $plan->amount_year }}">
                            @if ($errors->has('amount_year'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('amount_year') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mx-n2">
                    <div class="col-12 col-lg-6 px-2">
                        <div class="form-group">
                            <label for="i-tax-rates">{{ __('Tax rates') }}</label>
                            <select name="tax_rates[]" id="i-tax-rates" class="custom-select{{ $errors->has('tax_rates') ? ' is-invalid' : '' }}" size="3" multiple>
                                @foreach($taxRates as $taxRate)
                                    <option value="{{ $taxRate->id }}" @if(old('taxRates') !== null && in_array($taxRate->id, old('taxRates')) || old('taxRates') == null && is_array($plan->tax_rates) && in_array($taxRate->id, $plan->tax_rates)) selected @endif>{{ $taxRate->name }} ({{ number_format($taxRate->percentage, 2, __('.'), __(',')) }}% {{ ($taxRate->type ? __('Exclusive') : __('Inclusive')) }})</option>
                                @endforeach
                            </select>
                            @if ($errors->has('tax_rates'))
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $errors->first('tax_rates') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 px-2">
                        <div class="form-group">
                            <label for="i-coupons">{{ __('Coupons') }}</label>
                            <select name="coupons[]" id="i-coupons" class="custom-select{{ $errors->has('coupons') ? ' is-invalid' : '' }}" size="3" multiple>
                                @foreach($coupons as $coupon)
                                    <option value="{{ $coupon->id }}" @if(old('coupons') !== null && in_array($coupon->id, old('coupons')) || old('coupons') == null && is_array($plan->coupons) && in_array($coupon->id, $plan->coupons)) selected @endif>{{ $coupon->code }} ({{ number_format($coupon->percentage, 2, __('.'), __(',')) }}% {{ ($coupon->type ? __('Redeemable') : __('Discount')) }})</option>
                                @endforeach
                            </select>
                            @if ($errors->has('coupons'))
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $errors->first('coupons') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="form-group">
                <label for="i-visibility">{{ __('Visibility') }}</label>
                <select name="visibility" id="i-visibility" class="custom-select{{ $errors->has('visibility') ? ' is-invalid' : '' }}">
                    @foreach([1 => __('Public'), 0 => __('Unlisted')] as $key => $value)
                        <option value="{{ $key }}" @if((old('visibility') !== null && old('visibility') == $key) || ($plan->visibility == $key && old('visibility') == null)) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('visibility'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('visibility') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-position">{{ __('Position') }}</label>
                <input type="number" name="position" id="i-position" class="form-control{{ $errors->has('position') ? ' is-invalid' : '' }}" value="{{ old('position') ?? $plan->position }}">
                @if ($errors->has('position'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('position') }}</strong>
                    </span>
                @endif
            </div>

            <div class="hr-text"><span class="font-weight-medium text-body">{{ __('Features') }}</span></div>

            <div class="form-group">
                <label for="i-features-pageviews">{{ __('Pageviews') }}</label>
                <input type="text" name="features[pageviews]" id="i-features-pageviews" class="form-control{{ $errors->has('features.pageviews') ? ' is-invalid' : '' }}" value="{{ old('features.pageviews') ?? $plan->features->pageviews }}">
                @if ($errors->has('features.pageviews'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('features.pageviews') }}</strong>
                    </span>
                @endif
                <small class="form-text text-muted">{!! __(':value for unlimited.', ['value' => '<code class="badge badge-secondary">-1</code>']) !!} {!! __(':value for none.', ['value' => '<code class="badge badge-secondary">0</code>']) !!} {!! __(':value for number.', ['value' => '<code class="badge badge-secondary">N</code>']) !!}</small>
            </div>

            <div class="form-group">
                <label for="i-features-websites">{{ __('Websites') }}</label>
                <input type="text" name="features[websites]" id="i-features-websites" class="form-control{{ $errors->has('features.websites') ? ' is-invalid' : '' }}" value="{{ old('features.websites') ?? $plan->features->websites }}">
                @if ($errors->has('features.websites'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('features.websites') }}</strong>
                    </span>
                @endif
                <small class="form-text text-muted">{!! __(':value for unlimited.', ['value' => '<code class="badge badge-secondary">-1</code>']) !!} {!! __(':value for none.', ['value' => '<code class="badge badge-secondary">0</code>']) !!} {!! __(':value for number.', ['value' => '<code class="badge badge-secondary">N</code>']) !!}</small>
            </div>

            <div class="form-group">
                <label for="i-features-email-reports">{{ __('Email reports') }}</label>
                <select name="features[email_reports]" id="i-features-email-reports" class="custom-select{{ $errors->has('features.email_reports') ? ' is-invalid' : '' }}">
                    @foreach([1 => __('On'), 0 => __('Off')] as $key => $value)
                        <option value="{{ $key }}" @if((old('features.email_reports') !== null && old('features.email_reports') == $key) || ($plan->features->email_reports == $key && old('features.email_reports') == null)) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('features.email_reports'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('features.email_reports') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-features-data-export">{{ __('Data export') }}</label>
                <select name="features[data_export]" id="i-features-data-export" class="custom-select{{ $errors->has('features.data_export') ? ' is-invalid' : '' }}">
                    @foreach([1 => __('On'), 0 => __('Off')] as $key => $value)
                        <option value="{{ $key }}" @if((old('features.data_export') !== null && old('features.data_export') == $key) || ($plan->features->data_export == $key && old('features.data_export') == null)) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('features.data_export'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('features.data_export') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-features-api">{{ __('API') }}</label>
                <select name="features[api]" id="i-features-api" class="custom-select{{ $errors->has('features.api') ? ' is-invalid' : '' }}">
                    @foreach([1 => __('On'), 0 => __('Off')] as $key => $value)
                        <option value="{{ $key }}" @if((old('features.api') !== null && old('features.api') == $key) || ($plan->features->api == $key && old('features.api') == null)) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('features.api'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('features.api') }}</strong>
                    </span>
                @endif
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>
    </div>
</div>
