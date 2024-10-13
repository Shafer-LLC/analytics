@section('site_title', formatTitle([__('New'), __('Website'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['title' => __('New')],
]])

<h1 class="h2 mb-3 d-inline-block">{{ __('New') }}</h1>

<div class="card border-0 shadow-sm">
    <div class="card-header">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('Website') }}</div>
            </div>
        </div>
    </div>

    <div class="card-body">
        @include('shared.message')

        <form action="{{ route('websites.new') }}" method="post" enctype="multipart/form-data" id="form-website">
            @csrf

            <div class="form-group">
                <label for="i-domain">{{ __('Domain') }}</label>
                <input type="text" dir="ltr" name="domain" class="form-control{{ $errors->has('domain') ? ' is-invalid' : '' }}" id="i-domain" value="{{ old('domain') }}" placeholder="example.com">
                @if ($errors->has('domain'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('domain') }}</strong>
                    </span>
                @endif
                <small class="form-text form-text text-muted w-100">{{ __('Add a domain or subdomain.') }}</small>
            </div>

            <hr>

            <div class="form-group">
                <label>{{ __('Privacy') }}</label>
                <div class="form-group mb-0">
                    <div class="row mx-n2">
                        <div class="col-12 col-lg-4 px-2">
                            <div class="custom-control custom-radio">
                                <input type="radio" id="i-privacy1" name="privacy" class="custom-control-input{{ $errors->has('privacy') ? ' is-invalid' : '' }}" value="1" @if(old('privacy') == null || old('privacy') == 1) checked @endif>
                                <label class="custom-control-label cursor-pointer w-100 d-flex flex-column" for="i-privacy1">
                                    <span>{{ __('Private') }}</span>
                                    <span class="small text-muted">{{ __('Stats accessible only by you.') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="col-12 col-lg-4 px-2">
                            <div class="custom-control custom-radio">
                                <input type="radio" id="i-privacy0" name="privacy" class="custom-control-input{{ $errors->has('privacy') ? ' is-invalid' : '' }}" value="0" @if(old('privacy') == 0 && old('privacy') != null) checked @endif>
                                <label class="custom-control-label cursor-pointer w-100 d-flex flex-column" for="i-privacy0">
                                    <span>{{ __('Public') }}</span>
                                    <span class="small text-muted">{{ __('Stats accessible by anyone.') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="col-12 col-lg-4 px-2">
                            <div class="custom-control custom-radio">
                                <input type="radio" id="i-privacy2" name="privacy" class="custom-control-input{{ $errors->has('privacy') ? ' is-invalid' : '' }}" value="2" @if(old('privacy') == 2) checked @endif>
                                <label class="custom-control-label cursor-pointer w-100 d-flex flex-column" for="i-privacy2">
                                    <span>{{ __('Password') }}</span>
                                    <span class="small text-muted">{{ __('Stats accessible by password.') }}</span>
                                </label>
                                <div id="input-password" class="{{ (old('privacy') != 2 ? 'd-none' : '')}}">
                                    <div class="input-group mt-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text cursor-pointer" data-tooltip="true" data-title="{{ __('Show password') }}" data-password="i-password" data-password-show="{{ __('Show password') }}" data-password-hide="{{ __('Hide password') }}">@include('icons.lock', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                                        </div>
                                        <input id="i-password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ old('password') }}" autocomplete="new-password">
                                    </div>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($errors->has('privacy'))
                        <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $errors->first('privacy') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <hr>

            <div class="form-group">
                <div class="row mx-n2">
                    <div class="col-12 col-lg-4 px-2">
                        <div class="form-group mb-0">
                            <div class="row">
                                <div class="col"><label>{{ __('Notifications') }}</label></div>
                                <div class="col-auto">
                                    @cannot('emailReports', ['App\Models\User'])
                                        @if(paymentProcessors())
                                            <a href="{{ route('pricing') }}" data-tooltip="true" title="{{ __('Unlock feature') }}">@include('icons.lock-open', ['class' => 'fill-current text-primary width-4 height-4'])</a>
                                        @endif
                                    @endcannot
                                </div>
                            </div>

                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="email" value="1" class="custom-control-input {{ $errors->has('email') ? ' is-invalid' : '' }}" id="customCheckbox2" @if(old('email')) checked @endif @cannot('emailReports', ['App\Models\User']) disabled @endcannot>
                                <label class="custom-control-label cursor-pointer" for="customCheckbox2">
                                    <div>{{ __('Email') }}</div>
                                    <div class="small text-muted">{{ __('Periodic email reports.') }}</div>
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="row mx-n2">
                <div class="col-12 col-md-6 px-2">
                    <div class="form-group">
                        <label for="i-exclude-ips" class="d-flex align-items-center">{{ __('Exclude IPs') }} <span data-tooltip="true" title="{{ __('To block entire IP classes, use the CIDR notation.') }}" class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">@include('icons.info', ['class' => 'fill-current text-muted width-4 height-4'])</span></label>
                        <textarea name="exclude_ips" id="i-exclude-ips" class="form-control{{ $errors->has('exclude_ips') ? ' is-invalid' : '' }}">{{ old('exclude_ips') }}</textarea>
                        @if ($errors->has('exclude_ips'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('exclude_ips') }}</strong>
                            </span>
                        @endif
                        <small class="form-text text-muted">{{ __('One per line.') }}</small>
                    </div>
                </div>

                <div class="col-12 col-md-6 px-2">
                    <div class="form-group">
                        <label for="i-exclude-params">{{ __('Exclude URL query parameters') }}</label>
                        <textarea name="exclude_params" id="i-exclude-params" class="form-control{{ $errors->has('exclude_params') ? ' is-invalid' : '' }}">{{ old('exclude_params') }}</textarea>
                        @if ($errors->has('exclude_params'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('exclude_params') }}</strong>
                            </span>
                        @endif
                        <small class="form-text text-muted">{{ __('One per line.') }}</small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="hidden" name="exclude_bots" value="0">
                    <input type="checkbox" name="exclude_bots" value="1" class="custom-control-input {{ $errors->has('exclude_bots') ? ' is-invalid' : '' }}" id="customCheckbox3" @if(old('exclude_bots') || old('exclude_bots') == null) checked @endif>
                    <label class="custom-control-label cursor-pointer" for="customCheckbox3">
                        <div>{{ __('Exclude bots') }}</div>
                        <div class="small text-muted">{{ __('Exclude common bots from being tracked.') }}</div>
                        @if ($errors->has('exclude_bots'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('exclude_bots') }}</strong>
                            </span>
                        @endif
                    </label>
                </div>
            </div>

            <hr>

            <div class="form-group">
                @include('shared.tracking-code')
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>
    </div>
</div>
