@extends('simple-bookstore.layout')

@section('title', 'Order Details | ' . config('app.name', 'KNM Bookstore'))
@section('description', 'Review the full details, items, status history, and contact information for your bookstore order.')

@section('extra_styles')
<style>
    :root {
        --muted: var(--knm-text-muted, #5c5c5c);
        --border: var(--knm-border, #e8e8e8);
        --border-strong: #c4c4c4;
        --radius-md: var(--knm-radius-card, 8px);
        --radius-sm: var(--knm-radius-input, 8px);
        --surface-muted: var(--knm-surface-subtle, #fafafa);
        --accent: var(--knm-accent, #0d9373);
        --text: var(--knm-text, #1a1a1a);
    }

    .detail-hero {
        display: grid;
        gap: 18px;
    }

    .detail-hero h1 {
        font-size: clamp(34px, 5vw, 50px);
        line-height: 1.02;
        letter-spacing: -0.05em;
    }

    .detail-hero p,
    .detail-note,
    .detail-address,
    .timeline-item p {
        color: var(--muted);
        line-height: 1.6;
    }

    .detail-grid {
        display: grid;
        gap: 24px;
        grid-template-columns: minmax(0, 1.45fr) minmax(280px, 0.85fr);
        align-items: start;
    }

    .detail-section,
    .summary-card {
        display: grid;
        gap: 18px;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 8px 12px;
        border-radius: 999px;
        border: 1px solid transparent;
        font-size: 13px;
        font-weight: 700;
        text-transform: capitalize;
    }

    .status-pill.pending {
        background: #f6efe0;
        border-color: #dbc8a0;
        color: #8a6022;
    }

    .status-pill.accepted {
        background: #ebf4f1;
        border-color: #c2d8d0;
        color: #315c56;
    }

    .status-pill.delivered {
        background: #edf6ee;
        border-color: #bfd6c0;
        color: #346245;
    }

    .status-pill.rejected,
    .status-pill.canceled {
        background: #fff1ef;
        border-color: #e5bdb6;
        color: #8d3d2f;
    }

    .status-pill.other {
        background: var(--surface-muted);
        border-color: var(--border);
        color: var(--text);
    }

    .detail-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .detail-meta span {
        display: inline-flex;
        align-items: center;
        min-height: 40px;
        padding: 9px 12px;
        border: 1px solid var(--border);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.78);
        color: var(--muted);
        font-size: 14px;
    }

    .item-list,
    .timeline-list,
    .summary-list {
        display: grid;
        gap: 14px;
    }

    .item-card,
    .timeline-item,
    .summary-row {
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        background: rgba(255, 255, 255, 0.82);
    }

    .item-card {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 8px;
        padding: 16px;
    }

    .item-card__content {
        display: grid;
        gap: 8px;
        min-width: 0;
    }

    .item-card__content strong {
        font-size: 20px;
        line-height: 1.15;
        letter-spacing: -0.03em;
    }

    .item-card__content span {
        color: var(--muted);
        font-size: 14px;
        line-height: 1.55;
    }

    .item-card__content .price {
        font-size: 17px;
        font-weight: 700;
        color: var(--text);
    }

    .summary-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 14px 16px;
    }

    .summary-row span {
        color: var(--muted);
        font-size: 14px;
    }

    .summary-row strong {
        font-size: 18px;
        line-height: 1.2;
    }

    .timeline-item {
        position: relative;
        padding: 16px 18px 16px 20px;
    }

    .timeline-item::before {
        content: "";
        position: absolute;
        left: 10px;
        top: 22px;
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: var(--accent);
    }

    .timeline-item strong {
        display: block;
        margin-bottom: 4px;
        font-size: 16px;
    }

    .timeline-item small {
        display: block;
        margin-bottom: 6px;
        color: var(--muted);
    }

    .detail-address {
        display: grid;
        gap: 6px;
    }

    @media (max-width: 960px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .item-card {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
@php
    $createdAt = \Carbon\Carbon::parse($order->created_at);
    $dateText = $createdAt->isSameDay(now()) ? $createdAt->diffForHumans() : $createdAt->format('d M Y h:i A');
    $statusClass = in_array($order->status, ['pending', 'accepted', 'delivered', 'rejected', 'canceled'], true) ? $order->status : 'other';
    $currencyText = html_entity_decode(trim(strip_tags(currency())));
    $timeline = $order->statuss ? $order->statuss->sortByDesc('created_at') : collect();
@endphp

<div class="knm-container knm-stack-6 knm-mt-6 knm-pb-nav" style="padding-top: 24px;">
    <section class="knm-card detail-hero" style="background: var(--surface-muted);">
        <div class="section-head">
            <div>
                <h1>Order {{ $order->id }}-{{ $createdAt->format('dmy') }}</h1>
                <p>Review the ordered books, current status, updates, and delivery information from the same website layout as the homepage.</p>
            </div>

            <span class="status-pill {{ $statusClass }}">
                {{ ucfirst(__(str_replace('-', ' ', $order->status))) }}
            </span>
        </div>

        <div class="detail-meta">
            <span>Placed {{ $dateText }}</span>
            <span>Final Amount {{ $currencyText }}{{ number_format((float) $order->final_amount, decimalPlace()) }}</span>
            <span>{{ $order->items ? $order->items->sum('quantity') : 0 }} total items</span>
        </div>

        <div class="knm-flex knm-gap-2 knm-mt-4">
            <a href="{{ route('website.order') }}" class="knm-btn knm-btn--ghost">Back to Orders</a>
            <a href="{{ route('home') }}" class="knm-btn knm-btn--primary">Browse More Books</a>
        </div>
    </section>

    <section class="detail-grid">
        <div class="stack">
            <section class="knm-card detail-section">
                <div class="section-head">
                    <div>
                        <h2>Ordered items</h2>
                        <p class="section-copy">Every book included in this order is listed below with quantity and price.</p>
                    </div>
                </div>

                <div class="item-list">
                    @foreach($order->items as $item)
                        <article class="item-card">
                            <div class="item-card__content">
                                <strong>{{ _local($item->product_name, $item->local_product_name) }}</strong>
                                <span>{{ _local($item->unit_type, $item->local_unit_type) }}: {{ $item->quantity }} {{ _local($item->unit_name, $item->local_unit_name) }}</span>
                                <div class="price">{{ $currencyText }}{{ number_format((float) $item->final_price, decimalPlace()) }}</div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="knm-card detail-section">
                <div class="section-head">
                    <div>
                        <h2>Order updates</h2>
                        <p class="section-copy">Status notes appear here in reverse chronological order.</p>
                    </div>
                </div>

                <div class="timeline-list">
                    @forelse($timeline as $status)
                        @php
                            $statusDate = \Carbon\Carbon::parse($status->created_at);
                            $statusText = $statusDate->isSameDay(now()) ? $statusDate->diffForHumans() : $statusDate->format('d M Y h:i A');
                        @endphp
                        <div class="timeline-item">
                            <strong>{{ ucfirst(str_replace('-', ' ', $status->status)) }}</strong>
                            <small>{{ $statusText }}</small>
                            <p>{{ $status->public_note }}</p>
                        </div>
                    @empty
                        <div class="timeline-item">
                            <strong>No updates yet</strong>
                            <p>The order has been created, but no public status notes are available yet.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <aside class="stack">
            <section class="knm-card summary-card">
                <div class="section-head">
                    <div>
                        <h2>Payment summary</h2>
                        <p class="section-copy">A simple breakdown of the order totals.</p>
                    </div>
                </div>

                <div class="summary-list">
                    <div class="summary-row">
                        <span>Total Amount</span>
                        <strong>{{ $currencyText }}{{ number_format((float) $order->total_amount, decimalPlace()) }}</strong>
                    </div>
                    <div class="summary-row">
                        <span>Delivery Charge</span>
                        <strong>{{ $currencyText }}{{ number_format((float) $order->delivery_charge, decimalPlace()) }}</strong>
                    </div>
                    <div class="summary-row">
                        <span>Final Amount</span>
                        <strong>{{ $currencyText }}{{ number_format((float) $order->final_amount, decimalPlace()) }}</strong>
                    </div>
                </div>
            </section>

            <section class="knm-card summary-card">
                <div class="section-head">
                    <div>
                        <h2>Contact information</h2>
                        <p class="section-copy">Delivery details attached to this order.</p>
                    </div>
                </div>

                <div class="detail-address">
                    <strong>{{ $order->address_name }}</strong>
                    <a href="tel:{{ $order->address_mobile }}" class="subtle-link">{{ $order->address_mobile }}</a>
                    <div>{{ $order->address_line_1 }}</div>
                    @if($order->address_line_2)
                        <div>{{ $order->address_line_2 }}</div>
                    @endif
                    @if($order->address_line_3)
                        <div>{{ $order->address_line_3 }}</div>
                    @endif
                    <div>{{ $order->address_location }}</div>
                </div>
            </section>
        </aside>
    </section>
</div>
@endsection
