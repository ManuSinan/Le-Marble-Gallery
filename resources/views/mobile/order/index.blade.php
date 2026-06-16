<div class="appHeader bg-primary text-light" style="background-color: #1F2937 !important;">
    <div class="left">
        <a href="#" class="back headerButton item" style="color: #fff !important;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><line x1="18" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        </a>
    </div>
    <div class="pageTitle" style="font-family: 'Playfair Display', serif; font-weight: 600; color: #fff !important;">{{ __('Quotations') }}</div>
    <div class="right">
        <a href="{{ route('mobile.cart') }}" class="headerButton cart" style="color: #fff !important;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            <span class="badge" style="background-color: #D4AF37; color: #1F2937;">{{ cartItemCount(null) }}</span>
        </a>
    </div>
</div>

<!-- App Capsule -->
<div class="appCapsule" style="background-color: #F8F8F8; padding-bottom: 80px;">
 
    @if($orders && $orders->count() > 0)
    <div class="section full mt-2 px-3">
        <ul class="listview address-list text flush transparent pt-1" style="background: transparent; border: none; padding: 0;">
           
           @foreach($orders as $order)
            @php
                $createdAt = \Carbon\Carbon::parse( $order->created_at );
                $dt = $createdAt->format('d M Y, h:i A');

                // Status Badge Color Mapping
                $badgeColor = '#6B7280'; // Slate/Grey default
                $badgeLabel = 'Draft';
                if ($order->status == 'accepted' || $order->status == 'delivered') {
                    $badgeColor = '#10B981'; // Emerald
                    $badgeLabel = 'Approved';
                } elseif ($order->status == 'on-the-way') {
                    $badgeColor = '#D4AF37'; // Luxury Gold
                    $badgeLabel = 'Sent';
                } elseif ($order->status == 'rejected' || $order->status == 'canceled') {
                    $badgeColor = '#EF4444'; // Red
                    $badgeLabel = 'Rejected';
                }
            @endphp
            <li class="mb-2 shadow-sm" style="background: #fff; border-radius: 8px; list-style: none; margin-bottom: 12px !important;">
                <a href="{{ route('mobile.order.detail', ['order' => $order->id]) }}" class="d-block text-dark p-3" style="text-decoration: none; position: relative;">
                    
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center">
                            <div class="mr-2" style="color: #D4AF37; background-color: #F9FBFD; padding: 8px; border-radius: 6px; display: inline-flex; align-items: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            </div>
                            <div>
                                <div class="font-weight-bold" style="font-family: 'Playfair Display', serif; font-size: 14px; color: #111827;">
                                    {{ $order->quotation_ref }}
                                </div>
                                <div style="font-size: 11px; color: #9CA3AF;">{{ $dt }}</div>
                            </div>
                        </div>
                        <span class="badge text-light" style="background-color: {{ $badgeColor }}; font-weight: bold; text-transform: uppercase; font-size: 9px; padding: 4px 8px; border-radius: 4px;">{{ $badgeLabel }}</span>
                    </div>

                    <div style="border-top: 1px solid #f3f4f6; margin-top: 8px; padding-top: 8px;">
                        <div class="d-flex justify-content-between" style="font-size: 12px; color: #374151;">
                            <div style="font-weight: 600;">Client: {{ $order->address_name }}</div>
                            <div style="color: #6B7280;">{{ $order->project_type }}</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div style="font-size: 11px; color: #6B7280;">Estimate Value:</div>
                            <div style="font-weight: 700; color: #1F2937; font-size: 14px;">₹{{ priceFormat($order->final_amount, '') }}</div>
                        </div>
                    </div>
                </a>
            </li>
           @endforeach

        </ul>
    </div>
    @else
 
    <div class="empty-products text-center py-5" style="background: white; margin: 24px 16px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div class="error-page">
            <div class="icon-box" style="color: #D4AF37; margin-bottom: 16px;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <h1 class="title" style="font-size: 18px; font-weight: bold; color: #111827; font-family: 'Playfair Display', serif;">{{ __('No quotations built yet!') }}</h1>
            <div class="text px-3 mb-4" style="font-size: 13px; color: #6B7280; font-family: 'Inter', sans-serif;">
                {{ __('Select premium marble slabs and configure layout sizes to start creating estimates.') }}
            </div>
            <a href="{{ route('mobile.products') }}" class="btn btn-primary" style="background-color: #1F2937 !important; border-color: #1F2937 !important; color: white; padding: 8px 20px; font-weight: bold; border-radius: 4px;">Build a Quote</a>
        </div>
    </div>

    @endif

</div>
<!-- * App Capsule -->

@include('mobile/layout/bottom-menu')