<div class="card ">
    <div class="card-body p-0 pb-1 pt-3">
        <div class="row g-2 align-items-center">
            <div class="col-auto"> 
                <span class="avatar avatar-md fill-bg">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M40.9706 31.0294C38.3566 28.4154 35.2452 26.4803 31.8505 25.3089C35.4863 22.8048 37.875 18.6139 37.875 13.875C37.875 6.22434 31.6507 0 24 0C16.3493 0 10.125 6.22434 10.125 13.875C10.125 18.6139 12.5137 22.8048 16.1496 25.3089C12.7549 26.4803 9.6435 28.4154 7.02947 31.0294C2.49647 35.5625 0 41.5894 0 48H3.75C3.75 36.8341 12.8341 27.75 24 27.75C35.1659 27.75 44.25 36.8341 44.25 48H48C48 41.5894 45.5035 35.5625 40.9706 31.0294ZM24 24C18.4171 24 13.875 19.458 13.875 13.875C13.875 8.292 18.4171 3.75 24 3.75C29.5829 3.75 34.125 8.292 34.125 13.875C34.125 19.458 29.5829 24 24 24Z" fill="#FFFBFB"/>
                        <path d="M44.9167 42H21.0833C20.4853 42 20 41.104 20 40C20 38.896 20.4853 38 21.0833 38H44.9167C45.5147 38 46 38.896 46 40C46 41.104 45.5147 42 44.9167 42Z" fill="white"/>
                        <path d="M44.4167 48H9.58333C8.70933 48 8 47.104 8 46C8 44.896 8.70933 44 9.58333 44H44.4167C45.2907 44 46 44.896 46 46C46 47.104 45.2907 48 44.4167 48Z" fill="white"/>
                    </svg>
                </span>
             </div>
            <div class="col ms-2">
            <h4 class="card-title m-0">
               {{ authUser()->name }}
            </h4>
            <div class="small mt-1">
                <span class="badge bg-green"></span> Online
            </div>

            </div> 
        </div>
    </div>
</div>



<div class="side-nav">

 
    <ul class="side-nav-group">
        <li class="nav-item-title">User</li>
 
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 8.71l-5.333 -4.148a2.666 2.666 0 0 0 -3.274 0l-5.334 4.148a2.665 2.665 0 0 0 -1.029 2.105v7.2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-7.2c0 -.823 -.38 -1.6 -1.03 -2.105" /><path d="M16 15c-2.21 1.333 -5.792 1.333 -8 0" /></svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Dashboard') }}
                </span>
            </a>
        </li>

        @if( hasPermission('manage.store') )    
        <li class="nav-item">
            <a class="nav-link" href="{{ route('manage.store') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><circle cx="12" cy="12" r="3" /></svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Manage Store') }}
                </span>
            </a>
        </li>
        @endif

 
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.update.profile') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 11l2 2l4 -4" /></svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Manage Profile') }}
                </span>
            </a>
        </li>
 
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.change.password') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="5" y="11" width="14" height="10" rx="2" /><circle cx="12" cy="16" r="1" /><path d="M8 11v-4a4 4 0 0 1 8 0v4" /></svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Change Password') }}
                </span>
            </a>
        </li>
 
        <li class="nav-item">
            <a class="nav-link" href="{{ route('signout') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">                
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Sign Out') }}
                </span>
            </a>
        </li> 



        <li class="nav-item-title">{{ __('E-commerce') }}</li>

        @if(hasPermission('order'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('order') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><rect x="9" y="3" width="6" height="4" rx="2" /><line x1="9" y1="12" x2="9.01" y2="12" /><line x1="13" y1="12" x2="15" y2="12" /><line x1="9" y1="16" x2="9.01" y2="16" /><line x1="13" y1="16" x2="15" y2="16" /></svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Orders') }}
                </span>
            </a>
        </li>
        @endif
 
        @if(hasPermission('product'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('product') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /><line x1="16" y1="5.25" x2="8" y2="9.75" /></svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Books') }}
                </span>
            </a>
        </li>
        @endif

        @if(hasPermission('stock'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('stock') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3.5 5.5l1.5 1.5l2.5 -2.5" /><path d="M3.5 11.5l1.5 1.5l2.5 -2.5" /><path d="M3.5 17.5l1.5 1.5l2.5 -2.5" /><line x1="11" y1="6" x2="20" y2="6" /><line x1="11" y1="12" x2="20" y2="12" /><line x1="11" y1="18" x2="20" y2="18" /></svg> 
                </span>
                <span class="nav-link-title">
                    {{ __('Limited Stock') }}
                </span>
            </a>
        </li>
        @endif

        @if(hasPermission('stock'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('stock', ['max' => 0, 'title' => 'Out of Stock']) }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="9" y1="6" x2="20" y2="6" /><line x1="9" y1="12" x2="20" y2="12" /><line x1="9" y1="18" x2="20" y2="18" /><line x1="5" y1="6" x2="5" y2="6.01" /><line x1="5" y1="12" x2="5" y2="12.01" /><line x1="5" y1="18" x2="5" y2="18.01" /></svg>                
                </span> 
                <span class="nav-link-title">
                    {{ __('Out of Stock') }}
                </span>
            </a>
        </li>
        @endif 

        @if(hasPermission('enquiry'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('enquiry') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 6h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" /><circle cx="17" cy="7" r="3" /></svg>                </span> 
                <span class="nav-link-title">
                    {{ __('Enquiry') }}
                </span>
            </a>
        </li>
        @endif 
       
        @if(hasPermission('attribute'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('attribute') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="4.6" y1="7" x2="19.4" y2="7" /><line x1="3" y1="12" x2="21" y2="12" /><line x1="4.6" y1="17" x2="19.4" y2="17" /></svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Attributes') }}
                </span>
            </a>
        </li>
        @endif

        @if(hasPermission('variant.option'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('variant.option') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="5" cy="6" r="2" /><circle cx="12" cy="6" r="2" /><circle cx="19" cy="6" r="2" /><circle cx="5" cy="18" r="2" /><circle cx="12" cy="18" r="2" /><line x1="5" y1="8" x2="5" y2="16" /><line x1="12" y1="8" x2="12" y2="16" /><path d="M19 8v2a2 2 0 0 1 -2 2h-12" /></svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Variant Options') }}
                </span>
            </a>
        </li>
        @endif


        @if(hasPermission('variant'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('variant') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 3h4v4h-4z" /><path d="M3 17h4v4h-4z" /><path d="M17 17h4v4h-4z" /><path d="M7 17l5 -4l5 4" /><line x1="12" y1="7" x2="12" y2="13" /></svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Variants') }}
                </span>
            </a>
        </li>
        @endif


 
        @if(hasPermission('unit'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('unit') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19.0588 7.05884L14.8235 14.8235H23.2941L19.0588 7.05884ZM4.94118 7.05884L0.705882 14.8235H9.17647L4.94118 7.05884ZM13.9963 4.9412C13.8934 5.23532 13.7261 5.49819 13.4945 5.7298C13.2629 5.96142 13 6.1287 12.7059 6.23164V20.4706H19.4118C19.5147 20.4706 19.5993 20.5037 19.6654 20.5699C19.7316 20.636 19.7647 20.7206 19.7647 20.8235V21.5294C19.7647 21.6324 19.7316 21.7169 19.6654 21.7831C19.5993 21.8493 19.5147 21.8824 19.4118 21.8824H4.58824C4.48529 21.8824 4.40074 21.8493 4.33456 21.7831C4.26838 21.7169 4.23529 21.6324 4.23529 21.5294V20.8235C4.23529 20.7206 4.26838 20.636 4.33456 20.5699C4.40074 20.5037 4.48529 20.4706 4.58824 20.4706H11.2941V6.23164C11 6.1287 10.7371 5.96142 10.5055 5.7298C10.2739 5.49819 10.1066 5.23532 10.0037 4.9412H4.58824C4.48529 4.9412 4.40074 4.90811 4.33456 4.84194C4.26838 4.77576 4.23529 4.6912 4.23529 4.58826V3.88238C4.23529 3.77944 4.26838 3.69488 4.33456 3.6287C4.40074 3.56253 4.48529 3.52944 4.58824 3.52944H10.0037C10.1581 3.11032 10.4154 2.77025 10.7757 2.50922C11.136 2.24819 11.5441 2.11768 12 2.11768C12.4559 2.11768 12.864 2.24819 13.2243 2.50922C13.5846 2.77025 13.8419 3.11032 13.9963 3.52944H19.4118C19.5147 3.52944 19.5993 3.56253 19.6654 3.6287C19.7316 3.69488 19.7647 3.77944 19.7647 3.88238V4.58826C19.7647 4.6912 19.7316 4.77576 19.6654 4.84194C19.5993 4.90811 19.5147 4.9412 19.4118 4.9412H13.9963ZM12 5.11767C12.2426 5.11767 12.4504 5.03127 12.6232 4.85848C12.796 4.68569 12.8824 4.47797 12.8824 4.23532C12.8824 3.99267 12.796 3.78495 12.6232 3.61216C12.4504 3.43936 12.2426 3.35297 12 3.35297C11.7574 3.35297 11.5496 3.43936 11.3768 3.61216C11.204 3.78495 11.1176 3.99267 11.1176 4.23532C11.1176 4.47797 11.204 4.68569 11.3768 4.85848C11.5496 5.03127 11.7574 5.11767 12 5.11767V5.11767ZM24 14.8235C24 15.3603 23.829 15.8419 23.4871 16.2684C23.1452 16.6949 22.7132 17.0294 22.1912 17.2721C21.6691 17.5147 21.1379 17.6967 20.5974 17.818C20.057 17.9393 19.5441 18 19.0588 18C18.5735 18 18.0607 17.9393 17.5202 17.818C16.9798 17.6967 16.4485 17.5147 15.9265 17.2721C15.4044 17.0294 14.9724 16.6949 14.6305 16.2684C14.2886 15.8419 14.1176 15.3603 14.1176 14.8235C14.1176 14.7427 14.2463 14.4449 14.5037 13.9302C14.761 13.4155 15.0993 12.7739 15.5184 12.0055C15.9375 11.2371 16.3309 10.5184 16.6985 9.84928C17.0662 9.18016 17.4412 8.5037 17.8235 7.81987C18.2059 7.13605 18.4118 6.7684 18.4412 6.71693C18.5735 6.47429 18.7794 6.35296 19.0588 6.35296C19.3382 6.35296 19.5441 6.47429 19.6765 6.71693C19.7059 6.7684 19.9118 7.13605 20.2941 7.81987C20.6765 8.5037 21.0515 9.18016 21.4191 9.84928C21.7868 10.5184 22.1801 11.2371 22.5993 12.0055C23.0184 12.7739 23.3566 13.4155 23.614 13.9302C23.8713 14.4449 24 14.7427 24 14.8235V14.8235ZM9.88235 14.8235C9.88235 15.3603 9.7114 15.8419 9.36949 16.2684C9.02757 16.6949 8.59559 17.0294 8.07353 17.2721C7.55147 17.5147 7.02022 17.6967 6.47978 17.818C5.93934 17.9393 5.42647 18 4.94118 18C4.45588 18 3.94301 17.9393 3.40257 17.818C2.86213 17.6967 2.33088 17.5147 1.80882 17.2721C1.28676 17.0294 0.854779 16.6949 0.512868 16.2684C0.170956 15.8419 0 15.3603 0 14.8235C0 14.7427 0.128676 14.4449 0.386029 13.9302C0.643382 13.4155 0.981618 12.7739 1.40074 12.0055C1.81985 11.2371 2.21324 10.5184 2.58088 9.84928C2.94853 9.18016 3.32353 8.5037 3.70588 7.81987C4.08824 7.13605 4.29412 6.7684 4.32353 6.71693C4.45588 6.47429 4.66176 6.35296 4.94118 6.35296C5.22059 6.35296 5.42647 6.47429 5.55882 6.71693C5.58824 6.7684 5.79412 7.13605 6.17647 7.81987C6.55882 8.5037 6.93382 9.18016 7.30147 9.84928C7.66912 10.5184 8.0625 11.2371 8.48162 12.0055C8.90074 12.7739 9.23897 13.4155 9.49632 13.9302C9.75368 14.4449 9.88235 14.7427 9.88235 14.8235V14.8235Z" fill="currentColor"/>
                    </svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Units') }}
                </span>
            </a>
        </li>
        @endif

        @if(hasPermission('tax'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('tax') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19.8002 2.5C19.8002 1.94772 19.3525 1.5 18.8002 1.5H5.20019C4.64791 1.5 4.2002 1.94772 4.2002 2.5V21.5C4.2002 22.0523 4.64791 22.5 5.2002 22.5H18.8002C19.3525 22.5 19.8002 22.0523 19.8002 21.5V2.5ZM18.6002 20.3C18.6002 20.8523 18.1525 21.3 17.6002 21.3H6.40019C5.84791 21.3 5.4002 20.8523 5.4002 20.3V3.7C5.4002 3.14772 5.84791 2.7 6.4002 2.7H17.6002C18.1525 2.7 18.6002 3.14772 18.6002 3.7V20.3Z" fill="currentColor"/>
                        <path d="M17.6998 11.1H6.2998V12.3H17.6998V11.1Z" fill="currentColor"/>
                        <path d="M17.6998 15H6.2998V16.2H17.6998V15Z" fill="currentColor"/>
                        <path d="M17.6998 18.9H6.2998V20.1H17.6998V18.9Z" fill="currentColor"/>
                        <path d="M7.95129 8.56741H8.98179V6.05611H10.0327V5.2326H6.90039V6.05611H7.95129V8.56741Z" fill="currentColor"/>
                        <path d="M10.8997 8.56739L11.0624 8.01691H12.2323L12.3992 8.56739H13.4736H13.4782H14.6246L15.2832 7.49599L15.9417 8.56739H17.0996L15.9637 6.84767L17.0018 5.2326H15.878L15.3037 6.25851L14.7134 5.2326H13.5782L14.6239 6.82949L13.4765 8.563L12.2252 5.2326H11.101L9.84766 8.56739H10.8997V8.56739ZM11.6504 6.09701L12.0185 7.2958H11.2861L11.6504 6.09701Z" fill="currentColor"/>
                    </svg>     
                </span>
                <span class="nav-link-title">
                    {{ __('Tax') }}
                </span>
            </a>
        </li>
        @endif

        @if(hasPermission('brand'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('brand') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><path d="M9 16v-8h4a2 2 0 0 1 0 4h-4m3 0l3 4" /></svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Publishers') }}
                </span>
            </a>
        </li>
        @endif

 
        @if(hasPermission('category'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('category') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5.5 5h13a1 1 0 0 1 .5 1.5l-5 5.5l0 7l-4 -3l0 -4l-5 -5.5a1 1 0 0 1 .5 -1.5" /></svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Categories') }}
                </span>
            </a>
        </li>
        @endif


        @if(hasPermission('location'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('location') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 0C7.34756 0 3.5625 3.78506 3.5625 8.4375C3.5625 10.0094 3.99792 11.5434 4.82198 12.8743L11.5197 23.6676C11.648 23.8744 11.874 24 12.1171 24C12.119 24 12.1208 24 12.1227 24C12.3679 23.9981 12.5944 23.8686 12.7204 23.6582L19.2474 12.7603C20.026 11.4576 20.4375 9.96277 20.4375 8.4375C20.4375 3.78506 16.6524 0 12 0ZM18.0406 12.0383L12.1065 21.9462L6.0172 12.1334C5.33128 11.0257 4.95938 9.74766 4.95938 8.4375C4.95938 4.56047 8.12297 1.39687 12 1.39687C15.877 1.39687 19.0359 4.56047 19.0359 8.4375C19.0359 9.7088 18.6885 10.9541 18.0406 12.0383Z" fill="currentColor"/>
                        <path d="M12 4.21875C9.67378 4.21875 7.78125 6.11128 7.78125 8.4375C7.78125 10.7489 9.64298 12.6562 12 12.6562C14.3861 12.6562 16.2188 10.7235 16.2188 8.4375C16.2188 6.11128 14.3262 4.21875 12 4.21875ZM12 11.2594C10.4411 11.2594 9.17813 9.9922 9.17813 8.4375C9.17813 6.88669 10.4492 5.61563 12 5.61563C13.5508 5.61563 14.8172 6.88669 14.8172 8.4375C14.8172 9.96952 13.5836 11.2594 12 11.2594Z" fill="currentColor"/>
                    </svg>
                </span>
                <span class="nav-link-title">
                 {{ __('Locations') }}
                </span>
            </a>
        </li>
        @endif

        @if(hasPermission('pincode'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('pincode') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 -9" /><path d="M3 12l18 -9" /><path d="M3 3l18 9" /><path d="M3 12l18 9" /></svg>
                </span>
                <span class="nav-link-title">{{ __('Pincodes') }}</span>
            </a>
        </li>
        @endif

        @if(hasPermission('state') && config('app.location_state'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('state') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 3l-6.5 18a0.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a0.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
                </span>
                <span class="nav-link-title">
                    {{ __('States') }}
                </span>
            </a>
        </li>
        @endif



        @if(hasPermission('banner.slider'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('banner.slider') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="4" x2="20" y2="4" /><line x1="4" y1="20" x2="20" y2="20" /><rect x="6" y="9" width="12" height="6" rx="2" /></svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Banner Sliders') }}
                </span>
            </a>
        </li>
        @endif
        @if(hasPermission('poster'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('poster') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="5" width="18" height="14" rx="2" /><path d="M3 12h18" /><path d="M7 9h2" /><path d="M7 15h2" /></svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Promotional Banners') }}
                </span>
            </a>
        </li>
        @endif

        <li class="nav-item">
            <a class="nav-link" href="{{ route('daily.offer') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="14" rx="3" /><path d="M4 12h16" /><path d="M8 9h2" /></svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Daily Offer') }}
                </span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('home.spotlight.index') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><path d="M9 13l2 2l4 -4" /></svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Home Sponsored') }}
                </span>
            </a>
        </li>

        @if( hasPermission('import.product') || hasPermission('import.product.edit'))
        <li class="nav-item-title">Import</li>
        @endif

        @if(hasPermission('import.product'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('import.product') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                 
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><polyline points="7 11 12 16 17 11" /><line x1="12" y1="4" x2="12" y2="16" /></svg>

                </span>
                <span class="nav-link-title">
                   {{ __('Book Bulk Insert') }}
                </span>
            </a>
        </li>
        @endif


        @if(hasPermission('import.product.edit'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('import.product.edit') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                 
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" /><path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" /><line x1="16" y1="5" x2="19" y2="8" /></svg>

                </span>
                <span class="nav-link-title">
                   {{ __('Book Bulk Update') }}
                </span>
            </a>
        </li>
        @endif



        @if(hasPermission('report.business.overview')  || hasPermission('report.most.purchased.categories') || hasPermission('report.most.purchased.brands') || hasPermission('report.most.purchased.products') || hasPermission('report.location'))
        <li class="nav-item-title">{{ __('Reports') }}</li>
        @endif

        @if(hasPermission('report.business.overview'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('report.business.overview') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3v18h18" /><circle cx="9" cy="9" r="2" /><circle cx="19" cy="7" r="2" /><circle cx="14" cy="15" r="2" /><line x1="10.16" y1="10.62" x2="12.5" y2="13.5" /><path d="M15.088 13.328l2.837 -4.586" /></svg>
                </span>
                <span class="nav-link-title">
                  {{ __('Business') }}
                </span>
            </a>
        </li>
        @endif

        @if(hasPermission('report.most.purchased.categories'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('report.most.purchased.categories') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5.5 5h13a1 1 0 0 1 .5 1.5l-5 5.5l0 7l-4 -3l0 -4l-5 -5.5a1 1 0 0 1 .5 -1.5" /></svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Categories') }}
                </span>
            </a>
        </li>
        @endif
 
        @if(hasPermission('report.most.purchased.brands'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('report.most.purchased.brands') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><path d="M9 16v-8h4a2 2 0 0 1 0 4h-4m3 0l3 4" /></svg>
                    </span>
                </span>
                <span class="nav-link-title">
                    {{ __('Publishers') }}
                </span>
            </a>
        </li>
        @endif
 
        @if(hasPermission('report.most.purchased.products'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('report.most.purchased.products') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /><line x1="16" y1="5.25" x2="8" y2="9.75" /></svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Books') }}
                </span>
            </a>
        </li>
        @endif

        @if(hasPermission('report.location'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('report.location') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 0C7.34756 0 3.5625 3.78506 3.5625 8.4375C3.5625 10.0094 3.99792 11.5434 4.82198 12.8743L11.5197 23.6676C11.648 23.8744 11.874 24 12.1171 24C12.119 24 12.1208 24 12.1227 24C12.3679 23.9981 12.5944 23.8686 12.7204 23.6582L19.2474 12.7603C20.026 11.4576 20.4375 9.96277 20.4375 8.4375C20.4375 3.78506 16.6524 0 12 0ZM18.0406 12.0383L12.1065 21.9462L6.0172 12.1334C5.33128 11.0257 4.95938 9.74766 4.95938 8.4375C4.95938 4.56047 8.12297 1.39687 12 1.39687C15.877 1.39687 19.0359 4.56047 19.0359 8.4375C19.0359 9.7088 18.6885 10.9541 18.0406 12.0383Z" fill="currentColor"/>
                        <path d="M12 4.21875C9.67378 4.21875 7.78125 6.11128 7.78125 8.4375C7.78125 10.7489 9.64298 12.6562 12 12.6562C14.3861 12.6562 16.2188 10.7235 16.2188 8.4375C16.2188 6.11128 14.3262 4.21875 12 4.21875ZM12 11.2594C10.4411 11.2594 9.17813 9.9922 9.17813 8.4375C9.17813 6.88669 10.4492 5.61563 12 5.61563C13.5508 5.61563 14.8172 6.88669 14.8172 8.4375C14.8172 9.96952 13.5836 11.2594 12 11.2594Z" fill="currentColor"/>
                    </svg>
                </span>
                <span class="nav-link-title">
                    {{ __('Location') }}
                </span>
            </a>
        </li>
        @endif

        @if( hasPermission('tc') || hasPermission('about.us') || hasPermission('safety.tips') ||  hasPermission('import.updates') || hasPermission('privacy.policy') )
        <li class="nav-item-title">Pages</li>
        @endif



        @if(hasPermission('about.us'))    
        <li class="nav-item">
            <a class="nav-link" href="{{ route('about.us') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="9" x2="10" y2="9" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="9" y1="17" x2="15" y2="17" /></svg>
                </span>
                <span class="nav-link-title">
                About Us
                </span>
            </a>
        </li>
        @endif

        @if(hasPermission('tc'))    
        <li class="nav-item">
            <a class="nav-link" href="{{ route('tc') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="9" x2="10" y2="9" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="9" y1="17" x2="15" y2="17" /></svg>
                </span>
                <span class="nav-link-title">
                    Terms and Conditions
                </span>
            </a>
        </li>
        @endif

        @if(hasPermission('safety.tips'))    
        <li class="nav-item">
            <a class="nav-link" href="{{ route('safety.tips') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block"> 
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="7" x2="10" y2="7" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="13" y1="17" x2="15" y2="17" /></svg> 
                </span>
                <span class="nav-link-title">
                    Shopping Tips
                </span>
            </a>
        </li>
        @endif

        @if(hasPermission('import.updates'))    
        <li class="nav-item">
            <a class="nav-link" href="{{ route('import.updates') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">

                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M11.5 21h-4.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v5m-5 6h7m-3 -3l3 3l-3 3" /></svg>
                </span>
                <span class="nav-link-title">
                    Store Updates
                </span>
            </a>
        </li>
        @endif

        @if(hasPermission('privacy.policy'))    
        <li class="nav-item">
            <a class="nav-link" href="{{ route('privacy.policy') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="3" y1="3" x2="21" y2="21" /><path d="M7 3h7l5 5v7m0 4a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-14" /></svg>
                </span>
                <span class="nav-link-title">
                    Privacy Policy
                </span>
            </a>
        </li>
        @endif

        

        @if( hasPermission('user') || hasPermission('role') || hasPermission('permission'))
        <li class="nav-item-title">{{ __('Manage Access') }}</li>
        @endif

        @if(hasPermission('user'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                 
                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 13.5C16.6193 13.5 15.5 12.3807 15.5 11C15.5 9.61929 16.6193 8.5 18 8.5C19.3807 8.5 20.5 9.61929 20.5 11C20.5 12.3807 19.3807 13.5 18 13.5ZM9 10.5C7.067 10.5 5.5 8.933 5.5 7C5.5 5.067 7.067 3.5 9 3.5C10.933 3.5 12.5 5.067 12.5 7C12.5 8.933 10.933 10.5 9 10.5Z" stroke="currentColor"/>
                        <path d="M20.0883 20.5C20.0038 18.6991 19.4654 17.0162 18.585 15.5639C19.8015 15.7101 20.87 16.1063 21.6923 16.7444C22.7182 17.5403 23.4024 18.7434 23.4993 20.4287C23.5004 20.4467 23.5005 20.4696 23.4976 20.4921C23.4974 20.4941 23.4971 20.4961 23.4968 20.4979C23.4865 20.4991 23.4731 20.5 23.4559 20.5H20.0883ZM23.5243 20.4922C23.5243 20.4922 23.524 20.4924 23.5232 20.4927L23.5243 20.4922ZM0.509074 20.1947C0.507031 20.191 0.505237 20.1876 0.503669 20.1845C0.697832 17.9658 1.68882 16.3164 3.16648 15.2101C4.66744 14.0863 6.70331 13.5 8.98334 13.5C11.3043 13.5 13.3713 14.0566 14.8806 15.1632C16.3749 16.2588 17.3608 17.9197 17.4988 20.2298C17.5006 20.2593 17.501 20.2996 17.4955 20.3416C17.4897 20.3846 17.4795 20.4154 17.469 20.4347C17.461 20.4494 17.4532 20.4579 17.4382 20.4663C17.4208 20.4761 17.3672 20.5 17.2467 20.5H17.225H17.2032H17.1814H17.1595H17.1376H17.1157H17.0937H17.0717H17.0496H17.0275H17.0054H16.9832H16.961H16.9387H16.9164H16.894H16.8716H16.8492H16.8267H16.8042H16.7817H16.7591H16.7364H16.7137H16.691H16.6682H16.6454H16.6226H16.5997H16.5768H16.5538H16.5308H16.5077H16.4847H16.4615H16.4383H16.4151H16.3919H16.3686H16.3452H16.3218H16.2984H16.275H16.2515H16.2279H16.2043H16.1807H16.157H16.1333H16.1096H16.0858H16.0619H16.0381H16.0141H15.9902H15.9662H15.9421H15.9181H15.8939H15.8698H15.8456H15.8213H15.797H15.7727H15.7483H15.7239H15.6995H15.675H15.6504H15.6259H15.6012H15.5766H15.5519H15.5271H15.5023H15.4775H15.4527H15.4278H15.4028H15.3778H15.3528H15.3277H15.3026H15.2774H15.2523H15.227H15.2017H15.1764H15.1511H15.1257H15.1002H15.0747H15.0492H15.0236H14.998H14.9724H14.9467H14.921H14.8952H14.8694H14.8435H14.8176H14.7917H14.7657H14.7397H14.7136H14.6875H14.6614H14.6352H14.609H14.5827H14.5564H14.5301H14.5037H14.4772H14.4508H14.4242H14.3977H14.3711H14.3445H14.3178H14.2911H14.2643H14.2375H14.2106H14.1838H14.1568H14.1299H14.1029H14.0758H14.0487H14.0216H13.9944H13.9672H13.9399H13.9126H13.8853H13.8579H13.8305H13.803H13.7755H13.748H13.7204H13.6928H13.6651H13.6374H13.6096H13.5819H13.554H13.5261H13.4982H13.4703H13.4423H13.4142H13.3862H13.358H13.3299H13.3017H13.2734H13.2451H13.2168H13.1884H13.16H13.1316H13.1031H13.0746H13.046H13.0174H12.9887H12.96H12.9313H12.9025H12.8737H12.8448H12.8159H12.7869H12.758H12.7289H12.6999H12.6708H12.6416H12.6124H12.5832H12.5539H12.5246H12.4952H12.4658H12.4364H12.4069H12.3774H12.3478H12.3182H12.2886H12.2589H12.2291H12.1994H12.1696H12.1397H12.1098H12.0799H12.0499H12.0199H11.9898H11.9597H11.9296H11.8994H11.8692H11.8389H11.8086H11.7783H11.7479H11.7174H11.687H11.6565H11.6259H11.5953H11.5647H11.534H11.5033H11.4725H11.4417H11.4109H11.38H11.3491H11.3181H11.2871H11.256H11.225H11.1938H11.1627H11.1314H11.1002H11.0689H11.0376H11.0062H10.9748H10.9433H10.9118H10.8803H10.8487H10.817H10.7854H10.7537H10.7219H10.6901H10.6583H10.6264H10.5945H10.5626H10.5306H10.4985H10.4664H10.4343H10.4022H10.37H10.3377H10.3054H10.2731H10.2407H10.2083H10.1759H10.1434H10.1109H10.0783H10.0457H10.013H9.98032H9.94758H9.9148H9.88198H9.84912H9.81622H9.78327H9.75029H9.71726H9.68419H9.65108H9.61794H9.58474H9.55151H9.51824H9.48492H9.45156H9.41817H9.38473H9.35125H9.31773H9.28417H9.25056H9.21692H9.18323H9.1495H9.11574H9.08193H9.04807H9.01418H8.98025H8.94627H8.91226H8.8782H8.8441H8.80996H8.77578H8.74156H8.7073H8.67299H8.63865H8.60426H8.56983H8.53536H8.50085H8.4663H8.43171H8.39708H8.3624H8.32768H8.29293H8.25813H8.22329H8.18841H8.15348H8.11852H8.08351H8.04847H8.01338H7.97825H7.94308H7.90787H7.87262H7.83732H7.80199H7.76661H7.7312H7.69574H7.66024H7.6247H7.58912H7.55349H7.51783H7.48212H7.44638H7.41059H7.37476H7.33889H7.30298H7.26702H7.23103H7.19499H7.15892H7.1228H7.08664H7.05044H7.0142H6.97791H6.94159H6.90522H6.86882H6.83237H6.79588H6.75935H6.72278H6.68617H6.64951H6.61282H6.57608H6.53931H6.50249H6.46563H6.42873H6.39178H6.3548H6.31778H6.28071H6.2436H6.20645H6.16927H6.13203H6.09476H6.05745H6.02009H5.9827H5.94526H5.90778H5.87026H5.8327H5.7951H5.75746H5.71978H5.68205H5.64428H5.60648H5.56863H5.53074H5.49281H5.45483H5.41682H5.37876H5.34067H5.30253H5.26435H5.22613H5.18787H5.14957H5.11122H5.07284H5.03441H4.99594H4.95744H4.91889H4.8803H4.84166H4.80299H4.76428H4.72552H4.68672H4.64788H4.609H4.57008H4.53112H4.49212H4.45307H4.41399H4.37486H4.33569H4.29648H4.25723H4.21794H4.17861H4.13924H4.09982H4.06036H4.02087H3.98133H3.94175H3.90213H3.86246H3.82276H3.78301H3.74323H3.7034H3.66353H3.62362H3.58367H3.54368H3.50365H3.46357H3.42345H3.3833H3.3431H3.30286H3.26258H3.22226H3.18189H3.14149H3.10104H3.06056H3.02003H2.97946H2.93885H2.8982H2.8575H2.81677H2.77599H2.73518H2.69432H2.65342H2.61248H2.5715H2.53047H2.48941H2.4483H2.40716H2.36597H2.32474H2.28347H2.24216H2.20081H2.15941H2.11798H2.0765H2.03499H1.99343H1.95183H1.91019H1.8685H1.82678H1.78502H1.74321H1.70136H1.65947H1.61754H1.57557H1.53356H1.49151H1.44941H1.40728H1.3651H1.32288H1.28062H1.23832H1.19598H1.1536H1.11117H1.06871H1.0262H0.983655H0.941066H0.898436H0.855764H0.813051H0.789446C0.787701 20.4989 0.785857 20.4977 0.783915 20.4963C0.742317 20.4684 0.687086 20.421 0.631483 20.3601C0.575901 20.2992 0.533504 20.2395 0.509074 20.1947Z" stroke="currentColor"/>
                    </svg>

                </span>
                <span class="nav-link-title">
                        {{ __('Users') }}
                </span>
            </a>
        </li>
        @endif
 
        @if(hasPermission('permission'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('permission') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 12l2 2l4 -4" /><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /></svg>
                </span>
                <span class="nav-link-title">
                        {{ __('Permission') }}
                </span>
            </a>
        </li>
        @endif

        @if(hasPermission('role'))    
        <li class="nav-item">
            <a class="nav-link" href="{{ route('role') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /><circle cx="12" cy="11" r="1" /><line x1="12" y1="12" x2="12" y2="14.5" /></svg>
                </span>
                <span class="nav-link-title">
                        {{ __('Role') }}
                </span>
            </a>
        </li>
        @endif


 
    </ul>

</div>
