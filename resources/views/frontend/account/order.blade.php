@extends('simple-bookstore.layout')

@section('title', 'Your Orders | ' . config('app.name', 'KNM Bookstore'))
@section('description', 'Review your bookstore orders in the same simple website layout as the homepage.')

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

    .orders-hero {
        display: grid;
        gap: 18px;
    }

    .orders-hero h1 {
        font-size: clamp(36px, 5vw, 54px);
        line-height: 1.02;
        letter-spacing: -0.05em;
    }

    .orders-hero p {
        max-width: 56ch;
        color: var(--muted);
        line-height: 1.65;
        font-size: 16px;
    }

    .orders-hero__stats {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .orders-stat,
    .order-card,
    .order-empty {
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        background: rgba(255, 255, 255, 0.82);
    }

    .orders-stat {
        padding: 16px 18px;
    }

    .orders-stat strong {
        display: block;
        margin-bottom: 6px;
        font-size: 28px;
        line-height: 1;
        letter-spacing: -0.04em;
    }

    .orders-stat span {
        color: var(--muted);
        font-size: 14px;
        line-height: 1.5;
    }

    .orders-list {
        display: grid;
        gap: 16px;
    }

    .order-card {
        display: grid;
        gap: 18px;
        padding: 20px;
        transition: border-color 0.18s ease, background 0.18s ease;
    }

    .order-card:hover {
        border-color: var(--border-strong);
        background: #ffffff;
    }

    .order-card__top,
    .order-card__bottom {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }

    .order-card__ref {
        display: grid;
        gap: 6px;
    }

    .order-card__ref strong {
        font-size: 22px;
        line-height: 1.1;
        letter-spacing: -0.03em;
    }

    .order-card__ref span,
    .order-card__meta,
    .order-card__location {
        color: var(--muted);
        font-size: 14px;
        line-height: 1.55;
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

    .order-card__metrics {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        width: 100%;
    }

    .order-metric {
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 14px 16px;
        background: var(--surface-muted);
    }

    .order-metric span {
        display: block;
        margin-bottom: 5px;
        font-size: 12px;
        font-weight: 700;
        color: var(--accent);
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .order-metric strong {
        font-size: 18px;
        line-height: 1.2;
        letter-spacing: -0.02em;
    }

    .order-empty {
        display: grid;
        gap: 12px;
        padding: 24px;
    }

    .order-empty h2 {
        font-size: 30px;
        line-height: 1.08;
        letter-spacing: -0.04em;
    }

    .order-empty p {
        color: var(--muted);
        line-height: 1.6;
    }

    @media (max-width: 900px) {
        .orders-hero__stats,
        .order-card__metrics {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
@php
    $currencyText = html_entity_decode(trim(strip_tags(currency())));
    $totalOrders = $orders ? $orders->count() : 0;
    $activeOrders = $orders ? $orders->whereNotIn('status', ['delivered', 'rejected', 'canceled'])->count() : 0;
    $completedOrders = $orders ? $orders->where('status', 'delivered')->count() : 0;
@endphp

<div class="knm-container knm-stack-6 knm-mt-6 knm-pb-nav" style="padding-top: 24px;">
    <section class="knm-card orders-hero" style="background: var(--surface-muted);">
        <div>
            <h1>Your orders</h1>
            <p>Track current and past textbook orders from the same simple website layout used on the homepage.</p>
        </div>

        <div class="orders-hero__stats">
            <div class="orders-stat">
                <strong>{{ $totalOrders }}</strong>
                <span>Total orders placed.</span>
            </div>
            <div class="orders-stat">
                <strong>{{ $activeOrders }}</strong>
                <span>Orders currently in progress.</span>
            </div>
            <div class="orders-stat">
                <strong>{{ $completedOrders }}</strong>
                <span>Orders marked as delivered.</span>
            </div>
        </div>
    </section>

    @if($orders && $orders->count() > 0)
        <section class="orders-list">
            @foreach($orders as $order)
                @php
                    $createdAt = \Carbon\Carbon::parse($order->created_at);
                    $dateText = $createdAt->isSameDay(now()) ? $createdAt->diffForHumans() : $createdAt->format('d M Y h:i A');
                    $statusClass = in_array($order->status, ['pending', 'accepted', 'delivered', 'rejected', 'canceled'], true) ? $order->status : 'other';
                    $itemCount = $order->items ? $order->items->sum('quantity') : 0;
                @endphp
                <a class="order-card" href="{{ route('website.order.detail', ['order' => $order->id . '-' . $createdAt->format('dmy')]) }}">
                    <div class="order-card__top">
                        <div class="order-card__ref">
                            <strong>Order {{ $order->id }}-{{ $createdAt->format('dmy') }}</strong>
                            <span>{{ $dateText }}</span>
                        </div>

                        <span class="status-pill {{ $statusClass }}">
                            {{ ucfirst(__(str_replace('-', ' ', $order->status))) }}
                        </span>
                    </div>

                    <div class="order-card__metrics">
                        <div class="order-metric">
                            <span>Final Amount</span>
                            <strong>{{ $currencyText }}{{ number_format((float) $order->final_amount, decimalPlace()) }}</strong>
                        </div>
                        <div class="order-metric">
                            <span>Items</span>
                            <strong>{{ $itemCount }}</strong>
                        </div>
                        <div class="order-metric">
                            <span>Location</span>
                            <strong>{{ $order->address_location ?: 'Not provided' }}</strong>
                        </div>
                    </div>

                    <div class="order-card__bottom">
                        <div class="order-card__meta">Reference No. {{ $order->id }}-{{ $createdAt->format('dmy') }}</div>
                        <div class="order-card__location">Open order details</div>
                    </div>
                </a>
            @endforeach
        </section>
    @else
        <section class="order-empty">
            <h2>Your order list is empty.</h2>
            <p>Select books from the homepage and place your first order. Once an order is placed, it will appear here with its status and amount.</p>
            <div class="knm-mt-4">
                <a href="{{ route('home') }}" class="knm-btn knm-btn--primary">Browse Books</a>
            </div>
        </section>
    @endif
</div>
@endsection
