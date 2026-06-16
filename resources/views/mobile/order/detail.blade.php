<div class="appHeader bg-primary text-light" style="background-color: #1F2937 !important;">
    <div class="left">
        <a href="#" class="back headerButton item" style="color: #fff !important;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><line x1="18" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        </a>
    </div>
    <div class="pageTitle" style="font-family: 'Playfair Display', serif; font-weight: 600; color: #fff !important;">Quotation Details</div>
    <div class="right">
        <a href="{{ route('mobile.cart') }}" class="headerButton cart" style="color: #fff !important;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            <span class="badge" style="background-color: #D4AF37; color: #1F2937;">{{ cartItemCount(null) }}</span>
        </a>
    </div>
</div>

<!-- App Capsule -->
<div class="appCapsule" style="background-color: #F8F8F8; padding-bottom: 80px;">

    <!-- Action Workflow Box -->
    <div class="section mt-2 px-3">
        @php
            $createdAt = \Carbon\Carbon::parse( $order->created_at );
            $dt = $createdAt->format('d M Y, h:i A');

            // Status Badge Color & Actions Mapping
            $badgeColor = '#6B7280';
            $badgeLabel = 'Draft';
            $actionTitle = 'Quotation Status';
            $actionDesc = 'This is a draft quotation estimate. Review details, adjust pricing variables and proceed to finalize.';
            $actionBtnText = 'Send to Client';
            $actionBtnColor = '#1F2937';
            $actionBtnTextCol = '#D4AF37';

            if ($order->status == 'accepted' || $order->status == 'delivered') {
                $badgeColor = '#10B981';
                $badgeLabel = 'Approved';
                $actionTitle = 'Signed & Approved';
                $actionDesc = 'This quotation has been approved by the client and signed off for material procurement.';
                $actionBtnText = 'Download PDF Agreement';
                $actionBtnColor = '#10B981';
                $actionBtnTextCol = '#fff';
            } elseif ($order->status == 'on-the-way') {
                $badgeColor = '#D4AF37';
                $badgeLabel = 'Sent';
                $actionTitle = 'Sent to Client';
                $actionDesc = 'Quotation details have been dispatched to client. Awaiting design approval and signature.';
                $actionBtnText = 'Send WhatsApp Reminder';
                $actionBtnColor = '#D4AF37';
                $actionBtnTextCol = '#1F2937';
            } elseif ($order->status == 'rejected' || $order->status == 'canceled') {
                $badgeColor = '#EF4444';
                $badgeLabel = 'Rejected / Expired';
                $actionTitle = 'Quotation Expired';
                $actionDesc = 'This estimate has been marked as rejected or has exceeded its 30-day validity window.';
                $actionBtnText = 'Duplicate & Revise';
                $actionBtnColor = '#EF4444';
                $actionBtnTextCol = '#fff';
            }
        @endphp
        
        <div class="card shadow-sm border-0" style="border-radius: 8px; background: #fff; padding: 16px;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge text-light" style="background-color: {{ $badgeColor }}; font-weight: bold; text-transform: uppercase; font-size: 9px; padding: 4px 8px; border-radius: 4px;">{{ $badgeLabel }}</span>
                <span style="font-size: 11px; color: #9CA3AF;">{{ $dt }}</span>
            </div>
            <h4 style="font-family: 'Playfair Display', serif; font-weight: bold; color: #111827; font-size: 16px; margin-bottom: 4px;">{{ $actionTitle }}</h4>
            <p style="font-size: 12px; color: #6B7280; line-height: 1.5; margin-bottom: 12px;">{{ $actionDesc }}</p>
            
            <a href="#" class="btn btn-block shadow-sm" style="background-color: {{ $actionBtnColor }}; color: {{ $actionBtnTextCol }}; font-weight: bold; font-family: 'Inter', sans-serif; font-size: 12px; border-radius: 4px; padding: 8px 16px;">
                {{ $actionBtnText }}
            </a>
        </div>
    </div>

    <!-- Quotation Info Card -->
    <div class="section mt-3 px-3">
        <div class="section-title" style="font-family: 'Playfair Display', serif; font-size: 14px; font-weight: bold; color: #1F2937; margin-bottom: 8px;">Quotation Info</div>
        <div class="card shadow-sm border-0" style="border-radius: 8px; background: #fff; padding: 16px; font-family: 'Inter', sans-serif; font-size: 13px;">
            <div class="d-flex justify-content-between mb-2">
                <div style="color: #6B7280;">Reference Number</div>
                <div class="font-weight-bold" style="font-family: 'Playfair Display', serif; color: #111827;">{{ $order->quotation_ref }}</div>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <div style="color: #6B7280;">Project Type</div>
                <div class="font-weight-bold" style="color: #111827;">{{ $order->project_type ?? 'Residential' }}</div>
            </div>
            @if($order->architect_name)
            <div class="d-flex justify-content-between mb-2">
                <div style="color: #6B7280;">Architect / Designer</div>
                <div class="font-weight-bold" style="color: #111827;">{{ $order->architect_name }}</div>
            </div>
            @endif
            <div class="d-flex justify-content-between mb-0">
                <div style="color: #6B7280;">Quotation Validity</div>
                <div class="font-weight-bold" style="color: #D4AF37;">30 Days (Until {{ $createdAt->addDays(30)->format('d M Y') }})</div>
            </div>
        </div>
    </div>

    <!-- Slabs / Materials List -->
    @if($order->items)
    <div class="section full products-list mt-3 px-3">
        <div class="section-title" style="font-family: 'Playfair Display', serif; font-size: 14px; font-weight: bold; color: #1F2937; margin-bottom: 8px;">Quoted Slabs & Materials</div>
        <ul class="listview image-listview media border-0 shadow-sm" style="background: #fff; border-radius: 8px; padding: 0;">
            @foreach($order->items as $item)
                <li style="border-bottom: 1px solid #f3f4f6; list-style: none; padding: 10px 12px;">
                    <div class="item d-flex align-items-center">
                        <div class="imageWrapper" style="width: 55px; height: 55px; border-radius: 4px; overflow: hidden; background: #e5e7eb;">
                            @if( $item->product_image )
                            <img src="{{ asset('uploads/' . $item->product_image) }}" alt="{{ $item->product_name }}" style="width: 55px; height: 55px; object-fit: cover;"/>
                            @else
                            <img src="{{ asset('assets/mobile/img/200x150-blank.png') }}" alt="{{ $item->product_name }}" style="width: 55px; height: 55px; object-fit: cover;"/>
                            @endif
                        </div>
                        <div class="in pl-2 flex-grow-1" style="padding-left: 10px !important;">
                            <div class="title" style="font-size: 12px; font-weight: 700; color: #111827; font-family: 'Inter', sans-serif;">{{ strtoupper($item->product_name) }}</div>
                            <div style="font-size: 11px; color: #6B7280; margin-top: 2px;">Finish: {{ $item->finish_type ?? 'Polished' }} | Thickness: {{ $item->thickness ?? '18mm' }}</div>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <div class="details" style="font-size: 11px; color: #6B7280;">{{ $item->quantity }} Sq.Ft @ ₹{{ number_format($item->selling_price, 0) }}/Sq.Ft</div>
                                <div class="price" style="font-weight: 700; color: #111827; font-size: 12px;">₹{{ priceFormat($item->final_price, '') }}</div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Price Breakdown Card -->
    <div class="section mt-3 px-3">
        <div class="section-title" style="font-family: 'Playfair Display', serif; font-size: 14px; font-weight: bold; color: #1F2937; margin-bottom: 8px;">Quotation Cost Breakdown</div>
        <div class="card shadow-sm border-0" style="border-radius: 8px; background: #fff; padding: 16px; font-family: 'Inter', sans-serif; font-size: 13px;">
            <div class="d-flex justify-content-between mb-2">
                <div style="color: #6B7280;">Material Subtotal</div>
                <div style="font-weight: 600; color: #111827;">₹{{ priceFormat($order->total_amount, '') }}</div>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <div style="color: #6B7280;">Cutting & Sizing</div>
                <div style="font-weight: 600; color: #111827;">₹{{ priceFormat($order->cutting_charge ?? 0, '') }}</div>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <div style="color: #6B7280;">Transportation Charge</div>
                <div style="font-weight: 600; color: #111827;">₹{{ priceFormat($order->delivery_charge ?? 0, '') }}</div>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <div style="color: #6B7280;">Installation Charge</div>
                <div style="font-weight: 600; color: #111827;">₹{{ priceFormat($order->installation_charge ?? 0, '') }}</div>
            </div>
            @if($order->discount_amount > 0)
            <div class="d-flex justify-content-between mb-2">
                <div style="color: #EF4444;">Gallery Discount</div>
                <div style="font-weight: 700; color: #EF4444;">-₹{{ priceFormat($order->discount_amount, '') }}</div>
            </div>
            @endif
            <hr style="border-color: #f3f4f6; margin: 12px 0;">
            <div class="d-flex justify-content-between align-items-center">
                <div style="font-size: 14px; font-weight: 700; color: #1F2937; font-family: 'Playfair Display', serif;">Total Estimate Value</div>
                <div style="font-weight: 800; color: #D4AF37; font-size: 16px; font-family: 'Playfair Display', serif;">₹{{ priceFormat($order->final_amount, '') }}</div>
            </div>
        </div>
    </div>

    <!-- Client Site Address -->
    <div class="section mt-3 px-3">
        <div class="section-title" style="font-family: 'Playfair Display', serif; font-size: 14px; font-weight: bold; color: #1F2937; margin-bottom: 8px;">{{ __('Site Location Details') }}</div>
        <div class="deliver-to shadow-sm p-3" style="background: #fff; border-radius: 8px; font-family: 'Inter', sans-serif;">
            <div class="d-flex align-items-center mb-1">
                <span class="badge badge-primary mr-2" style="background-color: #1F2937; color: #D4AF37; font-weight: bold; text-transform: uppercase; font-size: 9px; padding: 3px 6px;">{{ __( $order->address_type ) }}</span>
                <div class="name" style="font-weight: 700; color: #111827; font-family: 'Playfair Display', serif; font-size: 14px;">{{ $order->address_name }}</div>
            </div>
            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Contact Number: {{ $order->address_mobile }}</div>
            <div style="font-size: 12px; color: #374151;">{{ $order->address_line_1 }}, {{ $order->address_line_2 }}</div>
            @if($order->address_line_3)
            <div style="font-size: 12px; color: #6B7280; font-style: italic;">Landmark: {{ $order->address_line_3 }}</div>
            @endif
            <div style="font-size: 12px; color: #D4AF37; font-weight: bold; margin-top: 4px;">Zone: {{ _local( $order->address_location, $order->address_local_location) }}</div>
        </div>
    </div>

    <!-- Need Help Section -->
    @php $orderEnquiryNumber = getOption('order_enquiry_number'); @endphp
    @if($orderEnquiryNumber)
    <div class="section mt-3 mb-5 px-3">
        <div class="section-title" style="font-family: 'Playfair Display', serif; font-size: 14px; font-weight: bold; color: #1F2937; margin-bottom: 8px;">{{ __('Need Assistance?') }}</div>
        <div class="shadow-sm p-3" style="background: #fff; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px; color: #4B5563;">
            <p class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1" style="position: relative; top: -1px; stroke: #D4AF37;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> Call our Stone Experts: <a href="tel:{{ $orderEnquiryNumber }}" class="external font-weight-bold" style="color: #D4AF37;">{{ $orderEnquiryNumber }}</a></p>
        </div>
    </div>
    @endif

</div>
<!-- * App Capsule -->

@include('mobile/layout/bottom-menu')