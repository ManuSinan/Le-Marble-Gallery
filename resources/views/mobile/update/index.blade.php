<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="pageTitle">{{ __( config('app.name', 'Laravel') ) }}</div>
</div>
<!-- * App Header -->
 
<!-- App Capsule -->
<div class="appCapsule">

    <div class="empty-products">
            <div class="error-page mt-5">
                <div class="icon-box text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 18a3.5 3.5 0 0 0 0 -7h-1a5 4.5 0 0 0 -11 -2a4.6 4.4 0 0 0 -2.1 8.4" /><line x1="12" y1="13" x2="12" y2="22" /><polyline points="9 19 12 22 15 19" /></svg>
                </div>
                <h1 class="title">{{  __('You are running an old version!') }}</h1>
                <a href="{{ $link }}" class="btn btn-primary btn-block">{{ __('Update Now') }}</a>
            </div>

    <div>
 
</div>
<!-- * App Capsule -->