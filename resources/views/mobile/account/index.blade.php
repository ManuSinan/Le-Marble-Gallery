<div class="appHeader bg-primary text-light" style="background-color: #1F2937 !important; height: 56px; border-bottom: none;">
    <div class="left">
        <a href="#" class="back headerButton item" style="color: #fff !important;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><line x1="18" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        </a>
    </div>
    <div class="pageTitle" style="font-family: 'Playfair Display', serif; font-weight: 600; color: #fff !important; font-size: 17px;">{{ __('Account') }}</div>
    <div class="right">
        <a href="{{ route('mobile.cart',['referral' => route('mobile.account')]) }}" class="headerButton cart" style="color: #fff !important;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            <span class="badge" style="background-color: #D4AF37; color: #1F2937; position: absolute; top: 5px; right: 5px; font-size: 9px; padding: 2px 5px; border-radius: 50%;">{{ cartItemCount(null) }}</span>
        </a>
    </div>
</div>
  
<!-- App Capsule -->
<div class="appCapsule" style="background-color: #F8F9FA; padding-top: 56px; padding-bottom: 80px;">
    
    <!-- Profile Card -->
    <div class="section mt-2 px-3">
        <div class="profile-card shadow-sm" style="background: #fff; border-radius: 12px; padding: 18px 16px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.03) !important;">
            <div class="d-flex align-items-center">
                <div class="avatar mr-3" style="background: #E8F0FE; color: #1B6EF3; width: 52px; height: 52px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 14px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1B6EF3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <div class="in">
                    <h3 class="name mb-0" style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 15px; color: #111827; letter-spacing: -0.2px;">@if($authUser) {{ strtoupper($authUser->name) }} @else {{ __('GUEST USER') }} @endif</h3>
                    <h5 class="subtext mb-0" style="font-family: 'Inter', sans-serif; font-size: 13px; color: #6B7280; margin-top: 3px;">@if($authUser) {{ $authUser->mobile }} @else {{ __('Sign in / Register') }} @endif</h5>
                </div>
            </div>
            @if($authUser)
            <a href="{{ route('mobile.edit.profile') }}" style="color: #9CA3AF;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            @endif
        </div>
    </div>

    <!-- Actions List -->
    <div class="section mt-3 px-3">
        <div class="card shadow-sm border-0" style="border-radius: 12px; background: #fff; overflow: hidden; box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.03) !important;">
            <ul class="listview image-listview text" style="padding: 0; margin: 0; list-style: none;">
                
                @if($authUser)
                    <!-- Update Profile -->
                    <li style="border-bottom: 1px solid #F3F4F6;">
                        <a href="{{ route('mobile.edit.profile') }}" class="item d-flex align-items-center justify-content-between p-3 text-dark" style="text-decoration: none; font-family: 'Inter', sans-serif; font-size: 13px;">
                            <div class="d-flex align-items-center">
                                <div style="background-color: #EEF4FF; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1B6EF3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                </div>
                                <div style="font-weight: 500; color: #1F2937;">{{ __('Update Profile') }}</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                    </li>

                    <!-- My Quotations -->
                    <li style="border-bottom: 1px solid #F3F4F6;">
                        <a href="{{ route('mobile.orders') }}" class="item d-flex align-items-center justify-content-between p-3 text-dark" style="text-decoration: none; font-family: 'Inter', sans-serif; font-size: 13px;">
                            <div class="d-flex align-items-center">
                                <div style="background-color: #FDF4E5; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                </div>
                                <div style="font-weight: 500; color: #1F2937;">{{ __('My Quotations') }}</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                    </li>

                    <!-- Delivery Sites -->
                    <li style="border-bottom: 1px solid #F3F4F6;">
                        <a href="{{ route('mobile.address') }}" class="item d-flex align-items-center justify-content-between p-3 text-dark" style="text-decoration: none; font-family: 'Inter', sans-serif; font-size: 13px;">
                            <div class="d-flex align-items-center">
                                <div style="background-color: #E6F7F0; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                </div>
                                <div style="font-weight: 500; color: #1F2937;">{{ __('Delivery Sites & Addresses') }}</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                    </li>

                    <!-- Change Password -->
                    <li style="border-bottom: 1px solid #F3F4F6;">
                        <a href="{{ route('mobile.change.password') }}" class="item d-flex align-items-center justify-content-between p-3 text-dark" style="text-decoration: none; font-family: 'Inter', sans-serif; font-size: 13px;">
                            <div class="d-flex align-items-center">
                                <div style="background-color: #FEECEB; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                </div>
                                <div style="font-weight: 500; color: #1F2937;">{{ __('Change Password') }}</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                    </li>

                    <!-- Sign Out -->
                    <li>
                        <a href="{{ route('mobile.signout') }}" class="item d-flex align-items-center justify-content-between p-3" style="text-decoration: none; font-family: 'Inter', sans-serif; font-size: 13px; color: #EF4444 !important;">
                            <div class="d-flex align-items-center">
                                <div style="background-color: #FEECEB; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                </div>
                                <div style="font-weight: 500; color: #EF4444;">{{ __('Sign Out') }}</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="stroke: #EF4444;"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                    </li>
                @else
                    <!-- Sign In -->
                    <li style="border-bottom: 1px solid #F3F4F6;">
                        <a href="{{ route('mobile.signin') }}" class="item d-flex align-items-center justify-content-between p-3 text-dark" style="text-decoration: none; font-family: 'Inter', sans-serif; font-size: 13px;">
                            <div class="d-flex align-items-center">
                                <div style="background-color: #EEF4FF; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1B6EF3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                                </div>
                                <div style="font-weight: 500; color: #1F2937;">{{ __('Sign In') }}</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                    </li>
                    
                    <!-- Register -->
                    <li>
                        <a href="{{ route('mobile.signup') }}" class="item d-flex align-items-center justify-content-between p-3 text-dark" style="text-decoration: none; font-family: 'Inter', sans-serif; font-size: 13px;">
                            <div class="d-flex align-items-center">
                                <div style="background-color: #E6F7F0; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                                </div>
                                <div style="font-weight: 500; color: #1F2937;">{{ __('Register') }}</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                    </li>
                @endif
 
            </ul>
        </div>
    </div>
</div>
<!-- * App Capsule -->
@include('mobile/layout/bottom-menu')