@php
    $siteName = $storeName ?? config('app.name', 'Lee Marble Gallery');
    $contact = $contactNumber ?? getOption('order_enquiry_number', '');
@endphp
<footer class="knm-footer">
    <div class="knm-container knm-footer__grid">
        <div>
            <h3>Links</h3>
            <ul>
                <li><a href="{{ route('website.tc') }}">Terms</a></li>
                <li><a href="{{ route('website.privacy.policy') }}">Privacy</a></li>
                <li><a href="{{ route('website.about.us') }}">About</a></li>
            </ul>
        </div>
        <div>
            <h3>Contact</h3>
            <ul>
                <li>
                    @if($contact)
                        <a href="tel:{{ preg_replace('/\s+/', '', $contact) }}">Order help: {{ $contact }}</a>
                    @else
                        <span>Order help: call the store</span>
                    @endif
                </li>
                <li><a href="{{ route('signin') }}">Sign in</a></li>
            </ul>
        </div>
        <div>
            <h3>Newsletter</h3>
            <p class="knm-muted knm-small">We do not run an email list yet. Check this space later, or call the store for new arrivals.</p>
        </div>
    </div>
</footer>
