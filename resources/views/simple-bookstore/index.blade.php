@extends('simple-bookstore.layout')

@section('title', 'Order your products — ' . ($storeName ?? config('app.name')))

@php
    $allCount = $regularClasses->sum('count');
@endphp

@section('extra_styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap');

:root {
    --ink: #1a1a2e;
    --teal: #0f7173;
    --teal-light: #1a9a9c;
    --amber: #e8aa3e;
    --amber-light: #f5c966;
    --cream: #ffffff;
    --muted: #8a8a9a;
    --cs: 0 4px 24px rgba(15, 113, 115, .11);
}

        .knm-body--store {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
        }

        .knm-body--store.knm-hero-home .knm-header {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 70;
            background: transparent !important;
            border-bottom: none !important;
            box-shadow: none !important;
        }

        .knm-body--store.knm-hero-home .knm-header .knm-brand span,
        .knm-body--store.knm-hero-home .knm-header .knm-btn--ghost,
        .knm-body--store.knm-hero-home .knm-header .knm-btn--icon {
            color: #fff;
        }

        .knm-body--store.knm-hero-home .knm-header .knm-btn--ghost,
        .knm-body--store.knm-hero-home .knm-header .knm-btn--icon,
        .knm-body--store.knm-hero-home .knm-header .knm-btn--primary {
            background: #4f5162 !important;
            border: 1px solid #737789 !important;
            color: #ffffff !important;
            box-shadow: none !important;
        }

        .knm-body--store.knm-hero-home .knm-header .knm-btn--ghost:hover,
        .knm-body--store.knm-hero-home .knm-header .knm-btn--icon:hover,
        .knm-body--store.knm-hero-home .knm-header .knm-btn--primary:hover {
            background: #4f5162 !important;
            border-color: #737789 !important;
        }

        .knm-body--store.knm-hero-home .knm-header-actions__badge {
            background: var(--amber);
            color: #1a1a2e;
        }


/* Scoped Redesign CSS */
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
input[type=number] { -moz-appearance: textfield; }

/* ─── Book Grid Cards ─────────────────────────────── */
#books-root {
    display: grid !important;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
    flex-direction: unset !important;
}
#books-root.knm-hidden {
    display: none !important;
}
@media(min-width: 640px)  { #books-root { grid-template-columns: repeat(3, 1fr); } }
@media(min-width: 1200px) { #books-root { grid-template-columns: repeat(3, 1fr); } }

.knm-book-card-v2 {
    background: #fff;
    border: 1px solid #ebebeb;
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}
.knm-book-card-v2:hover {
    box-shadow: 0 8px 32px rgba(0,0,0,0.10);
    transform: translateY(-2px);
}
.knm-book-card-v2__cover {
    width: 100%;
    aspect-ratio: 3 / 4;
    background: #f0ede8;
    overflow: hidden;
    position: relative;
    border-radius: 12px 12px 0 0;
}
.knm-book-card-v2__cover img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
}
.knm-book-card-v2__cover-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(145deg, #e8e0d6, #d6cec4);
    color: #a09080;
}
.knm-book-card-v2__body {
    padding: 14px 14px 12px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.knm-book-card-v2__title {
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    color: #1a1a1a;
    line-height: 1.3;
    margin: 0;
}
.knm-book-card-v2__subject {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #9b9b9b;
    margin: 0;
    margin-top: 2px;
}
.knm-book-card-v2__footer {
    padding: 0 14px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
    gap: 8px;
}
.knm-book-card-v2__price {
    font-size: 13px;
    font-weight: 700;
    color: #1a1a1a;
}
.knm-book-card-v2__add {
    width: 36px; height: 36px;
    border-radius: 50%;
    border: 1.5px solid #e0e0e0;
    background: #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    color: #1a1a1a;
    transition: background 0.15s, border-color 0.15s;
    flex-shrink: 0;
}
.knm-book-card-v2__add:hover {
    background: #1a1a1a;
    border-color: #1a1a1a;
    color: #fff;
}
.knm-book-card-v2__add:disabled {
    opacity: 0.4;
    cursor: not-allowed;
    pointer-events: none;
}
.knm-book-card-v2__stepper {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.knm-book-card-v2__stepper button {
    width: 28px; height: 28px;
    border-radius: 50%;
    border: 1.5px solid #e0e0e0;
    background: #fff;
    font-weight: 700;
    font-size: 16px;
    line-height: 1;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #1a1a1a;
    transition: background 0.15s, border-color 0.15s;
}
.knm-book-card-v2__stepper button:hover {
    background: #1a1a1a;
    border-color: #1a1a1a;
    color: #fff;
}
.knm-book-card-v2__stepper input {
    width: 32px;
    text-align: center;
    border: none;
    background: transparent;
    font-weight: 700;
    font-size: 13px;
    color: #1a1a1a;
    padding: 0;
}
.knm-hero-new {
    position: relative;
    overflow: hidden;
    text-align: center;
    background: linear-gradient(145deg, #1a1a2e 0%, #16213e 55%, #0a3d62 100%);
    padding: 110px 24px 64px;
    border-radius: 0;
    width: 100vw;
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
    margin-top: 0;
    margin-bottom: 0;
    color: #fff;
    overflow: hidden;
}
.knm-hero-new::before {
    content: '';
    position: absolute;
    inset: 0;
    pointer-events: none;
    background: radial-gradient(ellipse 60% 50% at 18% 82%, rgba(15, 113, 115, .38) 0%, transparent 62%),
      radial-gradient(ellipse 50% 55% at 82% 18%, rgba(232, 170, 62, .22) 0%, transparent 60%),
      radial-gradient(ellipse 40% 35% at 50% 50%, rgba(15, 113, 115, .10) 0%, transparent 70%);
}
.knm-hero-new::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--teal-light), var(--amber), var(--teal-light), transparent);
}
.knm-hero-content { position: relative; z-index: 3; text-align: center; max-width: 420px; margin: 0 auto; }
.knm-hero-tag {
    display: inline-block;
    background: rgba(232, 170, 62, .18);
    color: var(--amber-light);
    border: 1px solid rgba(232, 170, 62, .32);
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    padding: 5px 14px;
    border-radius: 20px;
    margin-bottom: 18px;
}
.knm-hero-content h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(30px, 8.5vw, 54px);
    font-weight: 900;
    line-height: 1.13;
    margin-bottom: 12px;
}
.knm-hero-content h1 em { font-style: normal; color: var(--amber-light); }
.knm-hero-content p {
    color: rgba(255, 255, 255, .62);
    font-size: 14px;
    font-weight: 300;
    line-height: 1.75;
    max-width: 340px;
    margin: 0 auto 24px;
}

.knm-hero-glass {
    position: relative;
    background: rgba(255, 255, 255, .07);
    backdrop-filter: blur(22px);
    border: 1px solid rgba(255, 255, 255, .14);
    border-radius: 22px;
    padding: 20px;
    max-width: 390px;
    margin: 0 auto;
    text-align: left;
}
.knm-hero-glass label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .9px;
    text-transform: uppercase;
    color: rgba(255, 255, 255, .52);
    margin-bottom: 10px;
}
.knm-hero-glass select { 
    width: 100%;
    appearance: none;
    border-radius: 12px;
    border: none;
    padding: 15px 44px 15px 16px;
    font-size: 15px;
    font-weight: 500;
    background: #fff;
    color: var(--ink);
    transition: .2s;
    -webkit-appearance: none; appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="%230f7173" stroke-width="2" xmlns="http://www.w3.org/2000/svg"><path d="M6 9l6 6 6-6"/></svg>');
    background-repeat: no-repeat; background-position: right 14px center;
}
.knm-browse-btn {
    width: 100%;
    margin-top: 12px;
    background: linear-gradient(135deg, var(--teal), var(--teal-light));
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 15px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
}
.knm-stats-strip { display: flex; justify-content: center; gap: 20px; padding-top: 18px; }
.knm-stat { text-align: center; }
.knm-stat-num { font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 700; color: var(--amber-light); }
.knm-stat-label { font-size: 10px; color: rgba(255,255,255,.42); text-transform: uppercase; }
.knm-stat-div { width: 1px; background: rgba(255,255,255,.14); }

.knm-home-section { padding: 28px 4px; }
.knm-home-sec-hdr { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
.knm-home-sec-title { font-family: 'Playfair Display', serif; font-size: 22px; font-weight: 700; color: var(--ink); }
.knm-home-view-all { font-size: 13px; font-weight: 600; color: var(--teal); text-decoration: none; background: transparent; border: none; }

.knm-bundle-banner { background: linear-gradient(135deg, #1e2a4a 0%, #16213e 100%); border-radius: 20px; padding: 22px; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; }
.knm-bundle-content { flex: 1; min-width: 250px; }
.knm-bundle-banner h3 { font-family: 'Playfair Display', serif; font-size: 19px; font-weight: 700; color: #fff; line-height: 1.3; margin: 0 0 6px 0; }
.knm-bundle-banner p { font-size: 12.5px; color: rgba(255,255,255,.55); line-height: 1.65; margin: 0; }
.knm-bundle-cta { background: var(--amber); color: var(--ink); border: none; border-radius: 14px; padding: 12px 16px; font-size: 13px; font-weight: 700; cursor: pointer; flex-shrink: 0; white-space: nowrap; margin-left: auto; }

.knm-special-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.knm-special-card { background: #fff; border-radius: 18px; box-shadow: var(--cs); padding: 18px 14px 14px; cursor: pointer; border: 1.5px solid transparent; text-align: left; }
.knm-special-card__title { font-family: 'Playfair Display', serif; font-size: 14px; font-weight: 700; color: var(--ink); }
.knm-special-card__sub { font-size: 11px; color: var(--muted); margin-top: 2px; }
.knm-special-card__cta { margin-top: 10px; font-size: 12px; font-weight: 600; color: var(--teal); }

.knm-carousel-outer { overflow: hidden; }
.knm-carousel-track { display: flex; gap: 14px; width: max-content; padding: 8px 0 10px; animation: knmCarouselScroll 28s linear infinite; }
@keyframes knmCarouselScroll { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
.knm-home-section--popular {
    padding-left: 0;
    padding-right: 0;
}
.knm-home-section--popular .knm-carousel-outer {
    width: 100vw;
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
}
.knm-home-section--popular .knm-carousel-track {
    padding-left: 0;
    padding-right: 0;
}
.knm-pop-book { width: 138px; flex-shrink: 0; background: #fff; border-radius: 18px; box-shadow: var(--cs); overflow: hidden; }
.knm-pop-book__cover { width: 100%; aspect-ratio: 3 / 4; background: #e8f5f5; }
.knm-pop-book__cover img { width: 100%; height: 100%; object-fit: cover; display: block; }
.knm-pop-book__info { padding: 10px 12px 13px; }
.knm-pop-book__title { font-size: 12px; font-weight: 600; color: var(--ink); line-height: 1.45; height: 34px; overflow: hidden; }
.knm-pop-book__class { font-size: 10px; color: var(--muted); margin: 5px 0 8px; }
.knm-pop-book__add { width: 28px; height: 28px; border-radius: 8px; background: var(--teal); color: #fff; border: none; cursor: pointer; }

/* Order drawer: flex column — only the list scrolls; header + subtotal + actions stay fixed at top/bottom */
#order-drawer {
  z-index: 100;
}
#order-drawer .knm-drawer {
  display: flex;
  flex-direction: column;
  min-height: 0;
  overflow: hidden;
  height: min(720px, 90vh);
  max-height: 90vh;
  background: #f8fafc;
  border-radius: 0;
  box-shadow: 0 -10px 36px rgba(2, 6, 23, 0.18);
}
@media (max-width: 1023px) {
  #order-drawer .knm-drawer {
    height: 100dvh;
    max-height: 100dvh;
  }
}
#order-drawer .knm-drawer__head {
  flex-shrink: 0;
  background: linear-gradient(135deg, #ffffff 0%, #ecfeff 100%);
  border-bottom: 1px solid #e2e8f0;
  padding: 14px 16px;
}
#order-drawer .knm-drawer__body {
  flex: 1 1 auto;
  min-height: 0;
  overflow-x: hidden;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
  padding: 14px 14px 8px;
  background: #f8fafc;
}
#order-drawer .knm-drawer__foot {
  position: sticky;
  bottom: 0;
  z-index: 2;
  flex-shrink: 0;
  margin-top: auto;
  background: #ffffff;
  border-top: 1px solid #e2e8f0;
  box-shadow: 0 -8px 20px rgba(15, 23, 42, 0.06);
  padding: 14px;
  padding-bottom: calc(18px + env(safe-area-inset-bottom, 0px));
}
#order-drawer .knm-drawer__foot .knm-grid-2 {
  gap: 10px;
}
#order-drawer .knm-drawer__foot .knm-btn {
  min-height: 42px;
  font-weight: 700;
}
#order-drawer #order-subtotal-mobile {
  color: #0f766e;
  font-size: 20px;
}
#order-drawer .knm-drawer__foot .knm-btn--primary {
  min-height: 46px;
  font-size: 15px;
}
#order-drawer .knm-drawer__foot .knm-btn--ghost {
  min-height: 40px;
}

/* Order rows: prevent overflow / broken layout */
.knm-order-row {
  border-radius: 14px;
  border: 1px solid #e5e7eb;
  background: #ffffff;
  box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
}
.knm-order-row__media {
  width: 52px;
  height: 70px;
  border-radius: 10px;
  overflow: hidden;
  background: #f1f5f9;
  border: 1px solid #e2e8f0;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}
.knm-order-row__media img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}
.knm-order-row__media--placeholder {
  color: #94a3b8;
}
.knm-order-row .knm-flex { min-width: 0; }
.knm-order-row .knm-grow { min-width: 0; }
.knm-order-row .knm-book-heading {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  font-weight: 700;
  color: #0f172a;
}
.knm-order-row .knm-muted {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  color: #64748b;
}
.knm-order-row .knm-btn {
  flex-shrink: 0;
}
.knm-order-row .remove-line {
  border-color: #fecaca;
  color: #b91c1c;
  background: #fff5f5;
}
.knm-order-row .remove-line:hover {
  border-color: #fca5a5;
  background: #fee2e2;
}

/* Classes drawer redesign (mobile sidebar) */
#sidebar-overlay {
  z-index: 90;
}
#sidebar-overlay .knm-drawer--left {
  display: flex;
  flex-direction: column;
  min-height: 0;
  height: 100dvh;
  max-height: 100dvh;
  background: linear-gradient(180deg, #f8fbff 0%, #f7fafc 100%);
  border-right: 1px solid #dbe7f3;
  box-shadow: 14px 0 38px rgba(15, 23, 42, 0.2);
  width: min(360px, 94vw);
  border-radius: 0 20px 20px 0;
  overflow: hidden;
}
#sidebar-overlay .knm-drawer__head {
  padding: 16px 16px 12px;
  border-bottom: 1px solid #dce8f2;
  background: linear-gradient(135deg, #ffffff 0%, #eef9ff 100%);
}
#sidebar-overlay .knm-drawer__head .knm-btn--icon {
  width: 34px;
  height: 34px;
  border-radius: 10px;
  border: 1px solid #e2e8f0;
  background: #fff;
}
#sidebar-overlay .knm-sidebar-title {
  margin: 0;
  font-size: 17px;
  font-weight: 900;
  color: #0f172a;
}
#sidebar-overlay .knm-sidebar-scroll {
  flex: 1 1 auto;
  min-height: 0;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
  padding: 12px 12px 16px;
  background: transparent;
}
#sidebar-overlay #class-list-mobile .knm-btn {
  border: 1px solid #deebf7;
  background: #ffffff;
  border-radius: 14px;
  margin-bottom: 10px;
  min-height: 48px;
  padding: 11px 13px;
  box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
}
#sidebar-overlay #class-list-mobile .knm-btn .knm-grow {
  font-weight: 800;
  font-size: 13px;
  color: #0f172a;
}
#sidebar-overlay #class-list-mobile .knm-btn .knm-class-count {
  font-size: 10px;
  font-weight: 700;
  color: #334155;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 999px;
  min-width: 26px;
  height: 26px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0 9px;
}
#sidebar-overlay #class-list-mobile .knm-btn.is-active {
  background: #f8fafc;
  border-color: #dbe3ec;
  box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
}
#sidebar-overlay #class-list-mobile .knm-btn.is-active .knm-grow {
  color: #0f172a;
}
#sidebar-overlay #class-list-mobile .knm-btn.is-active .knm-class-count {
  color: #334155;
  background: #eef2f7;
  border-color: #d9e2ec;
}
#sidebar-overlay #class-list-mobile .knm-btn:hover,
#sidebar-overlay #class-list-mobile .knm-btn:focus-visible {
  transform: none;
  box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
  background: #ffffff;
  border-color: #deebf7;
}
#sidebar-overlay #class-list-mobile .knm-btn.is-active:hover,
#sidebar-overlay #class-list-mobile .knm-btn.is-active:focus-visible {
  background: #f8fafc;
  border-color: #dbe3ec;
  box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
}
#sidebar-overlay .knm-drawer__foot {
  position: sticky;
  bottom: 0;
  z-index: 2;
  background: rgba(255, 255, 255, 0.92);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  border-top: 1px solid #dbe7f3;
  padding: 12px;
  padding-bottom: calc(14px + env(safe-area-inset-bottom, 0px));
}
#sidebar-overlay .knm-drawer__foot .knm-btn {
  min-height: 44px;
  border-radius: 11px;
  font-weight: 800;
  font-size: 13px;
}
#sidebar-overlay .knm-drawer__foot .knm-btn--primary {
  box-shadow: 0 6px 16px rgba(15, 118, 110, 0.18);
}




@media(max-width: 768px) {
    .knm-hero-new { padding: 104px 20px 56px; }
    .knm-hero-content h1 { font-size: 32px; margin-bottom: 12px; }
    .knm-hero-content p { font-size: 16px; margin-bottom: 24px; }
    .knm-special-grid { grid-template-columns: 1fr; }


}

/* Narrow mobile widths (e.g. iPhone XS/12 Pro): keep stepper inside card */
@media (max-width: 420px) {
    .knm-order-row__media {
        width: 48px;
        height: 64px;
    }
    #order-drawer .knm-drawer__body {
        padding-bottom: 12px;
    }
    #order-drawer .knm-drawer__foot .knm-grid-2 {
        display: grid;
        grid-template-columns: 1fr;
    }
    #order-drawer .knm-drawer__foot .knm-btn--primary {
        order: 1;
    }
    #order-drawer .knm-drawer__foot .knm-btn--ghost {
        order: 2;
    }
    .knm-book-card-v2__footer {
        gap: 6px;
    }
    .knm-book-card-v2__price {
        font-size: 12px;
        flex-shrink: 0;
    }
    .knm-book-card-v2__stepper {
        gap: 2px;
        min-width: 0;
    }
    .knm-book-card-v2__stepper button {
        width: 26px;
        height: 26px;
        font-size: 14px;
        line-height: 1;
        flex-shrink: 0;
    }
    .knm-book-card-v2__stepper input {
        width: 24px;
        font-size: 12px;
        flex-shrink: 0;
    }
}

/* Custom Footer */
.knm-footer { margin-top: 64px; }
.knm-footer-grid { display: grid; grid-template-columns: 2fr 1.5fr 1.5fr 1.5fr 1.5fr; gap: 32px; margin-bottom: 64px; }
.knm-footer-brand { display: flex; flex-direction: column; align-items: center; }
.knm-footer-logo { width: 140px; margin-bottom: 24px; }
.knm-footer-social { display: flex; gap: 16px; margin-top: auto; }
.knm-footer-social a { color: #64748b; transition: color 0.2s; }
.knm-footer-social a:hover { color: #1e3a8a; }
.knm-footer-heading { font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 20px; }
.knm-footer-link { display: block; font-size: 13px; color: #475569; margin-bottom: 12px; text-decoration: none; transition: color 0.2s; }
.knm-footer-link:hover { color: #06b6d4; }
.knm-footer-bottom { border-top: 1px solid #f1f5f9; padding: 24px 0; display: flex; justify-content: space-between; font-size: 13px; color: #64748b; }
@media(max-width: 1024px) {
    .knm-footer-grid { grid-template-columns: repeat(3, 1fr); }
    .knm-footer-brand { grid-column: 1 / -1; align-items: flex-start; margin-bottom: 32px; }
}
@media(max-width: 640px) {
    .knm-footer-grid { grid-template-columns: 1fr 1fr; }
    .knm-footer-bottom { flex-direction: column; gap: 16px; align-items: flex-start; }
}
</style>
@endsection

@section('content')
<div class="knm-container knm-pb-nav">
    <div class="knm-hero-new">
        <div class="knm-hero-content">
            <div class="knm-hero-tag">Lee Marble Gallery - {{ date('Y') }}</div>
            <h1>All Your Products<br><em>In One Place</em></h1>
            <p>Pick your category and find all required items in one place.</p>
            <div class="knm-hero-glass">
                <label for="knm-class-select">Select your Category</label>
                <select id="knm-class-select" name="class" aria-label="Select category" @if($regularClasses->isEmpty()) disabled @endif>
                    <option value="">{{ $regularClasses->isEmpty() ? 'No categories with products yet' : 'Select category…' }}</option>
                    @foreach ($regularClasses as $c)
                        <option value="{{ $c['id'] }}">{{ $c['name'] }} ({{ $c['count'] }} products)</option>
                    @endforeach
                </select>
                <button type="button" class="knm-browse-btn" id="browse-books-btn">Browse Products -&gt;</button>
                <div class="knm-stats-strip">
                    <div class="knm-stat">
                        <div class="knm-stat-num">{{ $products->count() }}+</div>
                        <div class="knm-stat-label">Products</div>
                    </div>
                    <div class="knm-stat-div"></div>
                    <div class="knm-stat">
                        <div class="knm-stat-num">{{ $regularClasses->count() }}</div>
                        <div class="knm-stat-label">Categories</div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="sidebar-overlay" class="knm-overlay knm-hidden" aria-hidden="true">
        <div class="knm-overlay__backdrop" id="sidebar-backdrop"></div>
        <aside class="knm-drawer knm-drawer--left" aria-label="Categories">
            <div class="knm-drawer__head">
                <div>
                    <p class="knm-sidebar-title">Choose your category</p>
                    <p class="knm-muted knm-small">Browse products faster by category</p>
                </div>
                <button type="button" class="knm-btn knm-btn--icon knm-btn--ghost" id="sidebar-close" aria-label="Close menu">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="knm-sidebar-scroll" id="class-list-mobile"></div>
            <div class="knm-drawer__foot knm-stack knm-gap-2">
                @if($authUser)
                    <a href="{{ route('website.order') }}" class="knm-btn knm-btn--ghost knm-btn--block">Orders</a>
                    <a href="{{ route('signout') }}" class="knm-btn knm-btn--ghost knm-btn--block">Sign out</a>
                @else
                    <a href="{{ route('signin') }}" data-auth-trigger="signin" class="knm-btn knm-btn--ghost knm-btn--block">Sign in</a>
                    <a href="{{ route('signup') }}" data-auth-trigger="signup" class="knm-btn knm-btn--primary knm-btn--block">Register</a>
                @endif
            </div>
        </aside>
    </div>

    <div id="store-layout" class="knm-store-layout">
        <aside id="desktop-sidebar" class="knm-desktop-sidebar">
            <h2 class="knm-sidebar-title">Categories</h2>
            <div class="knm-stack knm-gap-2" id="class-list-desktop"></div>
        </aside>

        <main id="main-content" class="knm-main">
            @if (session('order_success'))
                <div class="knm-notice knm-notice--success knm-mb-4" role="status">
                    Order placed. Reference <strong>{{ session('order_success.ref_no') }}</strong>. Product: {{ session('order_success.book') }}.
                </div>
            @endif

            @if ($errors->any())
                <div class="knm-notice knm-notice--error knm-mb-4" role="alert">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div id="empty-state" class="knm-hidden">
                <section class="knm-home-section">
                    <div class="knm-bundle-banner">
                        <div class="knm-bundle-content">
                            <h3>Get the Full Category Bundle &amp; Save</h3>
                            <p>Order all products for your category at once and get special bundle pricing + free delivery.</p>
                        </div>
                        <button type="button" class="knm-bundle-cta" id="special-view-all">Order Bundle -&gt;</button>
                    </div>
                </section>
                <section class="knm-home-section knm-home-section--popular">
                    <div class="knm-home-sec-hdr" style="padding: 0 4px;">
                        <h3 class="knm-home-sec-title">Popular Products</h3>
                    </div>
                    <div class="knm-carousel-outer">
                        <div class="knm-carousel-track" id="popular-books-track"></div>
                    </div>
                </section>
                <section class="knm-home-section">
                    <div class="knm-home-sec-hdr">
                        <h3 class="knm-home-sec-title">Other Items</h3>
                        <button type="button" class="knm-home-view-all" id="special-view-all-top">View All -&gt;</button>
                    </div>
                    <div id="special-sections-grid" class="knm-special-grid"></div>
                </section>
            </div>

            <div id="books-root" class="knm-hidden"></div>
        </main>

        <aside class="knm-desktop-order">
            <div class="knm-card">
                <div class="knm-flex-between knm-mb-4">
                    <h2 class="knm-acp-title">Your order</h2>
                    <button type="button" class="knm-btn knm-btn--sm knm-btn--ghost" id="clear-order-desktop">Clear</button>
                </div>
                <div class="knm-stack knm-gap-3 knm-order-scroll" id="order-items-desktop"></div>
                <div class="knm-order-summary-divider">
                    <div class="knm-flex-between knm-mb-4">
                        <span class="knm-muted">Subtotal</span>
                        <span class="knm-acp-title" id="order-subtotal-desktop">{{ $currencySymbol }}0.00</span>
                    </div>
                    @if($authUser)
                        <a href="{{ route('simple-bookstore.checkout') }}" class="knm-btn knm-btn--primary knm-btn--block">Proceed to checkout</a>
                    @else
                        <a href="{{ route('signin') }}" data-auth-trigger="signin" class="knm-btn knm-btn--primary knm-btn--block">Sign in to checkout</a>
                        <p class="knm-muted knm-small knm-mt-4">No account? <a class="knm-link" href="{{ route('signup') }}">Register</a></p>
                    @endif
                </div>
            </div>
        </aside>
    </div>
</div>

<div class="knm-orderbar knm-hidden" id="mobile-order-bar">
    <div class="knm-orderbar__inner">
        <div class="knm-grow">
            <div class="knm-small knm-orderbar-muted" id="mobile-bar-count">View order (0 items)</div>
            <div class="knm-acp-title" id="mobile-bar-total">{{ $currencySymbol }}0.00</div>
        </div>
        <button type="button" class="knm-btn knm-btn--primary knm-orderbar__cta" id="view-order-mobile">Review</button>
    </div>
</div>

<div id="order-drawer" class="knm-overlay knm-hidden" aria-hidden="true">
    <div class="knm-overlay__backdrop" id="order-backdrop"></div>
    <div class="knm-drawer knm-drawer--bottom knm-modal--desktop-card" role="dialog" aria-modal="true" aria-labelledby="order-drawer-title">
        <div class="knm-drawer__head">
            <span id="order-drawer-title" class="knm-acp-title">Your order</span>
            <button type="button" class="knm-btn knm-btn--icon knm-btn--ghost" id="order-close" aria-label="Close">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="knm-drawer__body knm-stack knm-gap-3" id="order-items-mobile"></div>
        <div class="knm-drawer__foot">
            <div class="knm-flex-between knm-mb-4">
                <span class="knm-muted">Subtotal</span>
                <span class="knm-acp-title" id="order-subtotal-mobile">{{ $currencySymbol }}0.00</span>
            </div>
            <div class="knm-grid-2">
                <button type="button" class="knm-btn knm-btn--ghost" id="clear-order-mobile">Clear order</button>
                @if($authUser)
                    <a href="{{ route('simple-bookstore.checkout') }}" class="knm-btn knm-btn--primary knm-btn--block">Checkout</a>
                @else
                    <a href="{{ route('signin') }}" data-auth-trigger="signin" class="knm-btn knm-btn--primary knm-btn--block">Sign in to checkout</a>
                @endif
            </div>
        </div>
    </div>
</div>

<footer class="knm-footer">
    <div class="knm-container">
        <div class="knm-footer-bottom" style="border-top: none;">
            <div>&copy; {{ date('Y') }} KNM Education Board. All rights reserved.</div>
            <div>Powered by: <a href="https://www.bluewhyte.com/" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: none;"><strong>BlueWhyte</strong></a></div>
        </div>
    </div>
</footer>

@endsection

@section('scripts')
<script>
(function () {
    const currencySymbol = @json($currencySymbol);
    const cartCookieName = '__cart';
    const classes = @json($regularClasses ?? []);
    const specialClasses = @json($specialClasses ?? []);
    const allCount = @json($allCount ?? 0);
    const products = @json($products ?? []);
    const subjectsByClass = @json($subjectsByClass ?? []);

    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const sidebarBackdrop = document.getElementById('sidebar-backdrop');
    const menuButton = document.getElementById('menu-button');
    const sidebarClose = document.getElementById('sidebar-close');
    const classListMobile = document.getElementById('class-list-mobile');
    const classListDesktop = document.getElementById('class-list-desktop');
    const desktopSidebar = document.getElementById('desktop-sidebar');
    const mainContent = document.getElementById('main-content');
    const storeLayout = document.getElementById('store-layout');
    const subjectChipRow = document.getElementById('subject-chip-row');

    const cartCountBadge = document.getElementById('cart-count-badge');
    const emptyState = document.getElementById('empty-state');
    const booksRoot = document.getElementById('books-root');
    const specialSectionsGrid = document.getElementById('special-sections-grid');
    const specialViewAll = document.getElementById('special-view-all');
    const specialViewAllTop = document.getElementById('special-view-all-top');
    const classSelect = document.getElementById('knm-class-select');
    const browseBooksBtn = document.getElementById('browse-books-btn');
    const popularBooksTrack = document.getElementById('popular-books-track');

    const mobileOrderBar = document.getElementById('mobile-order-bar');
    const mobileBarCount = document.getElementById('mobile-bar-count');
    const mobileBarTotal = document.getElementById('mobile-bar-total');
    const viewOrderMobile = document.getElementById('view-order-mobile');
    const cartButton = document.getElementById('cart-button');

    const orderDrawer = document.getElementById('order-drawer');
    const orderBackdrop = document.getElementById('order-backdrop');
    const orderClose = document.getElementById('order-close');
    const orderItemsMobile = document.getElementById('order-items-mobile');
    const orderItemsDesktop = document.getElementById('order-items-desktop');
    const orderSubtotalMobile = document.getElementById('order-subtotal-mobile');
    const orderSubtotalDesktop = document.getElementById('order-subtotal-desktop');
    const clearOrderMobile = document.getElementById('clear-order-mobile');
    const clearOrderDesktop = document.getElementById('clear-order-desktop');

    const searchToggle = document.getElementById('search-toggle');
    const searchPanel = document.getElementById('search-panel');

    const state = {
        selectedClassId: null,
        activeSubjectId: null,
        search: (@json($search ?? '')).toLowerCase(),
        sortBy: @json($sortby ?? 'subject'),
    };

    function money(value) {
        return `${currencySymbol}${Number(value).toFixed(2)}`;
    }

    function getCart() {
        const cookie = document.cookie.split('; ').find((row) => row.startsWith(`${cartCookieName}=`));
        if (!cookie) return { products: {} };
        try {
            const parsed = JSON.parse(decodeURIComponent(cookie.split('=').slice(1).join('=')));
            if (parsed && parsed.products && typeof parsed.products === 'object') return parsed;
        } catch (e) {}
        return { products: {} };
    }

    function setCart(cart) {
        document.cookie = `${cartCookieName}=${encodeURIComponent(JSON.stringify(cart))}; path=/; max-age=31556926`;
    }

    function clampQuantity(quantity, min, step, max) {
        let q = Number(quantity);
        const minimum = Number(min) || 1;
        const increment = Number(step) || 1;
        const maximum = max != null ? Number(max) : null;
        if (!Number.isFinite(q) || q < 0) q = 0;
        if (q === 0) return 0;
        if (q < minimum) q = minimum;
        const stepsFromMin = Math.round((q - minimum) / increment);
        q = minimum + Math.max(0, stepsFromMin) * increment;
        if (maximum && q > maximum) q = maximum;
        return q;
    }

    function cartSummary() {
        const cart = getCart();
        const prods = Object.values(cart.products || {});
        let subtotal = 0;
        let count = 0;
        prods.forEach((item) => {
            const q = Number(item.quantity || 0);
            if (q > 0) count += 1;
            subtotal += Number(item.total_selling_price || 0);
        });
        return { subtotal, count, cart };
    }

    function updateCartUI() {
        const { subtotal, count } = cartSummary();
        if (cartCountBadge) cartCountBadge.textContent = count;
        if (mobileOrderBar) mobileOrderBar.classList.toggle('knm-hidden', count <= 0 || subtotal <= 0);
        if (mobileBarCount) mobileBarCount.textContent = count === 1 ? 'View order (1 item)' : `View order (${count} items)`;
        if (mobileBarTotal) mobileBarTotal.textContent = money(subtotal);
        if (orderSubtotalMobile) orderSubtotalMobile.textContent = money(subtotal);
        if (orderSubtotalDesktop) orderSubtotalDesktop.textContent = money(subtotal);
    }

    function openSidebar() {
        if (!sidebarOverlay) return;
        sidebarOverlay.classList.remove('knm-hidden');
        sidebarOverlay.setAttribute('aria-hidden', 'false');
    }
    function setHeaderMode(isHeroHome) {
        document.body.classList.toggle('knm-hero-home', Boolean(isHeroHome));
    }
    function closeSidebar() {
        if (!sidebarOverlay) return;
        sidebarOverlay.classList.add('knm-hidden');
        sidebarOverlay.setAttribute('aria-hidden', 'true');
    }

    function openOrder() {
        if (!orderDrawer) return;
        orderDrawer.classList.remove('knm-hidden');
        orderDrawer.setAttribute('aria-hidden', 'false');
        renderOrder();
    }
    function closeOrder() {
        if (!orderDrawer) return;
        orderDrawer.classList.add('knm-hidden');
        orderDrawer.setAttribute('aria-hidden', 'true');
    }

    function productById(id) {
        return products.find((p) => String(p.id) === String(id));
    }

    function setItemQuantity(product, nextQty) {
        const cart = getCart();
        const productId = String(product.id);
        const max = product.stock_status === 'limited' ? Number(product.stock_available || 0) : null;
        const qty = clampQuantity(nextQty, product.minimum_quantity, product.stepper, max);
        if (qty <= 0) {
            if (cart.products && cart.products[productId]) delete cart.products[productId];
            setCart(cart);
            return;
        }
        if (!cart.products) cart.products = {};
        cart.products[productId] = {
            price: Number(product.mrp || 0),
            selling_price: Number(product.price || 0),
            quantity: qty,
            steper: Number(product.stepper || 1),
            total_price: Number(product.mrp || 0) * (qty / Number(product.stepper || 1)),
            total_selling_price: Number(product.price || 0) * (qty / Number(product.stepper || 1)),
            message: ''
        };
        setCart(cart);
    }

    function itemQuantity(productId) {
        const cart = getCart();
        const item = cart.products ? cart.products[String(productId)] : null;
        return item ? Number(item.quantity || 0) : 0;
    }

    function renderSubjectChips() {
        if (!subjectChipRow) return;
        subjectChipRow.innerHTML = '';
        const cid = state.selectedClassId;
        if (cid == null) {
            subjectChipRow.classList.add('knm-hidden');
            return;
        }
        const list = subjectsByClass[String(cid)] || [];
        subjectChipRow.classList.remove('knm-hidden');
        const allBtn = document.createElement('button');
        allBtn.type = 'button';
        allBtn.className = 'knm-chip' + (state.activeSubjectId == null ? ' is-active' : '');
        allBtn.textContent = 'All subcategories';
        allBtn.addEventListener('click', () => { state.activeSubjectId = null; renderSubjectChips(); renderBooks(); });
        subjectChipRow.appendChild(allBtn);
        list.forEach((s) => {
            const b = document.createElement('button');
            b.type = 'button';
            b.className = 'knm-chip' + (String(state.activeSubjectId) === String(s.id) ? ' is-active' : '');
            b.textContent = s.name || 'Subcategory';
            b.addEventListener('click', () => {
                state.activeSubjectId = state.activeSubjectId === s.id ? null : s.id;
                renderSubjectChips();
                renderBooks();
            });
            subjectChipRow.appendChild(b);
        });
    }

    function renderClassList(targetEl, isMobile) {
        if (!targetEl) return;
        const selected = state.selectedClassId;
        const makeBtn = (id, label, count) => {
            const button = document.createElement('button');
            button.type = 'button';
            const active = (selected == null && id == null) || String(selected) === String(id);
            button.className = 'knm-btn knm-btn--ghost knm-btn--block knm-btn--spread' + (active ? ' is-active' : '');
            button.innerHTML = `<span class="knm-grow">${label}</span><span class="knm-class-count">${count}</span>`;
            button.addEventListener('click', () => {
                state.selectedClassId = id;
                state.activeSubjectId = null;
                renderAll();
                if (isMobile) closeSidebar();
                if (id != null) window.scrollTo({ top: 0, behavior: 'smooth' });
            });
            return button;
        };
        targetEl.innerHTML = '';
        targetEl.appendChild(makeBtn(null, 'All categories', allCount));
        classes.forEach((c) => targetEl.appendChild(makeBtn(c.id, c.name, c.count)));
    }

    function attachSwipeRemove(rowEl, onRemove) {
        let startX = 0;
        rowEl.addEventListener('touchstart', (e) => { startX = e.changedTouches[0].clientX; rowEl.classList.remove('is-swipe-open'); }, { passive: true });
        rowEl.addEventListener('touchend', (e) => {
            const dx = e.changedTouches[0].clientX - startX;
            if (dx < -80) onRemove();
            else if (dx > 40) rowEl.classList.remove('is-swipe-open');
        }, { passive: true });
    }

    function renderBooks() {
        if (!booksRoot || !emptyState) return;
        const selectedClassId = state.selectedClassId;
        const search = (state.search || '').toLowerCase();
        const openBundleClass = () => {
            const classOne = classes.find((c) => {
                const name = String(c.name || '').toLowerCase().trim();
                return name === '1' || name === '1st' || name.startsWith('class 1') || name.includes(' 1');
            }) || classes[0];
            if (!classOne) return;
            state.selectedClassId = classOne.id;
            state.activeSubjectId = null;
            renderAll();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };

        if (selectedClassId == null) {
            setHeaderMode(true);
            if (menuButton) { menuButton.classList.add('knm-hidden'); menuButton.setAttribute('aria-hidden', 'true'); }
            if (desktopSidebar) desktopSidebar.classList.add('knm-hidden');
            if (storeLayout) storeLayout.classList.add('knm-store-layout--hero');
            if (mainContent) mainContent.classList.add('knm-main--wide');
            const desktopOrder = document.querySelector('.knm-desktop-order');
            if (desktopOrder) desktopOrder.classList.add('knm-hidden');
            const heroBanner = document.querySelector('.knm-hero-new');
            if (heroBanner) heroBanner.classList.remove('knm-hidden');
            closeSidebar();
            booksRoot.classList.add('knm-hidden');
            subjectChipRow?.classList.add('knm-hidden');
            emptyState.classList.remove('knm-hidden');
            if (specialSectionsGrid) {
                specialSectionsGrid.innerHTML = '';
                const accents = [
                    'linear-gradient(90deg,#0f7173,#1a9a9c)',
                    'linear-gradient(90deg,#e8aa3e,#f5c966)',
                    'linear-gradient(90deg,#43a047,#66bb6a)',
                    'linear-gradient(90deg,#e91e63,#f06292)',
                ];
                specialClasses.forEach((s) => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'knm-special-card';
                    const accent = accents[specialClasses.indexOf(s) % accents.length];
                    btn.innerHTML = `
                        <div style="height:3px;border-radius:18px 18px 0 0;margin:-18px -14px 12px;background:${accent};"></div>
                        <p class="knm-special-card__title">${s.name}</p>
                        <p class="knm-special-card__sub">${s.count} items available</p>
                        <div class="knm-special-card__cta">View list -&gt;</div>
                    `;
                    btn.addEventListener('click', () => {
                        state.selectedClassId = s.id;
                        state.activeSubjectId = null;
                        renderAll();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    });
                    specialSectionsGrid.appendChild(btn);
                });
            }
            if (popularBooksTrack) {
                popularBooksTrack.innerHTML = '';
                const popular = products.slice(0, 10);
                const renderSet = (set) => {
                    set.forEach((p) => {
                        const card = document.createElement('article');
                        card.className = 'knm-pop-book';
                        card.innerHTML = `
                            <div class="knm-pop-book__cover"><img src="${p.image_url || ''}" alt="${p.name}"></div>
                            <div class="knm-pop-book__info">
                                <div class="knm-pop-book__title">${p.name}</div>
                                <div class="knm-pop-book__class">${p.class_name || 'General'}</div>
                                <button type="button" class="knm-pop-book__add" aria-label="Add ${p.name}">+</button>
                            </div>
                        `;
                        card.querySelector('.knm-pop-book__add').addEventListener('click', (e) => {
                            e.stopPropagation();
                            setItemQuantity(p, (p.minimum_quantity || 1));
                            renderAll(true);
                        });
                        card.addEventListener('click', () => {
                            state.selectedClassId = Number(p.class_id || 0);
                            state.activeSubjectId = null;
                            renderAll();
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        });
                        popularBooksTrack.appendChild(card);
                    });
                };
                renderSet(popular);
                renderSet(popular);
            }

            specialViewAll?.addEventListener('click', () => {
                openBundleClass();
            });
            specialViewAllTop?.addEventListener('click', () => {
                openBundleClass();
            });

            return;
        }

        setHeaderMode(false);
        if (menuButton) { menuButton.classList.remove('knm-hidden'); menuButton.setAttribute('aria-hidden', 'false'); }
        if (desktopSidebar) desktopSidebar.classList.remove('knm-hidden');
        if (storeLayout) storeLayout.classList.remove('knm-store-layout--hero');
        if (mainContent) mainContent.classList.remove('knm-main--wide');
        const desktopOrder = document.querySelector('.knm-desktop-order');
        if (desktopOrder) desktopOrder.classList.remove('knm-hidden');
        emptyState.classList.add('knm-hidden');
        booksRoot.classList.remove('knm-hidden');
        const heroBanner = document.querySelector('.knm-hero-new');
        if (heroBanner) heroBanner.classList.add('knm-hidden');
        renderSubjectChips();

        let filtered = products.filter((p) => String(p.class_id) === String(selectedClassId));
        if (state.activeSubjectId != null) {
            filtered = filtered.filter((p) => String(p.subject_id) === String(state.activeSubjectId));
        }
        if (search) {
            filtered = filtered.filter((p) =>
                String(p.name).toLowerCase().includes(search) ||
                String(p.product_code || '').toLowerCase().includes(search) ||
                String(p.subject_name || '').toLowerCase().includes(search)
            );
        }

        const grouped = new Map();
        filtered.forEach((p) => {
            const key = String(p.subject_id || 0);
            const existing = grouped.get(key) || { subject: p.subject_name || 'General', items: [] };
            existing.items.push(p);
            grouped.set(key, existing);
        });
        const subjectSections = Array.from(grouped.values()).sort((a, b) => a.subject.localeCompare(b.subject));

        booksRoot.innerHTML = '';

        subjectSections.forEach((section) => {
            section.items.forEach((p) => {
                const q = itemQuantity(p.id);
                const max = p.stock_status === 'limited' ? Number(p.stock_available || 0) : null;
                const outOfStock = p.stock_status === 'limited' && max <= 0;

                const card = document.createElement('article');
                card.className = 'knm-book-card-v2';

                /* ── Cover ── */
                const cover = document.createElement('div');
                cover.className = 'knm-book-card-v2__cover';
                if (p.image_url) {
                    const img = document.createElement('img');
                    img.src = p.image_url;
                    img.alt = p.name;
                    img.loading = 'lazy';
                    cover.appendChild(img);
                } else {
                    cover.innerHTML = `<div class="knm-book-card-v2__cover-placeholder">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                          <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                          <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                        </svg>
                    </div>`;
                }

                /* ── Body (title + subject) ── */
                const body = document.createElement('div');
                body.className = 'knm-book-card-v2__body';
                body.innerHTML = `<p class="knm-book-card-v2__title">${p.name}</p>
                    <p class="knm-book-card-v2__subject">${p.subject_name || ''}</p>`;

                /* ── Footer (price + action) ── */
                const footer = document.createElement('div');
                footer.className = 'knm-book-card-v2__footer';

                const priceEl = document.createElement('span');
                priceEl.className = 'knm-book-card-v2__price';
                priceEl.textContent = money(p.price);

                footer.appendChild(priceEl);

                if (q <= 0) {
                    const addBtn = document.createElement('button');
                    addBtn.type = 'button';
                    addBtn.className = 'knm-book-card-v2__add';
                    addBtn.setAttribute('aria-label', outOfStock ? 'Out of stock' : 'Add to order');
                    addBtn.disabled = outOfStock;
                    addBtn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>`;
                    addBtn.addEventListener('click', () => {
                        if (!outOfStock) { setItemQuantity(p, p.minimum_quantity || 1); renderAll(true); }
                    });
                    footer.appendChild(addBtn);
                } else {
                    const stepEl = document.createElement('div');
                    stepEl.className = 'knm-book-card-v2__stepper';
                    const dec = document.createElement('button');
                    dec.type = 'button';
                    dec.textContent = '−';
                    dec.setAttribute('aria-label', 'Decrease quantity');
                    const inp = document.createElement('input');
                    inp.type = 'number';
                    inp.value = String(q);
                    inp.min = '0';
                    inp.step = String(p.stepper || 1);
                    if (max != null) inp.max = String(max);
                    const inc = document.createElement('button');
                    inc.type = 'button';
                    inc.textContent = '+';
                    inc.setAttribute('aria-label', 'Increase quantity');
                    const sync = () => {
                        const raw = Number(inp.value);
                        setItemQuantity(p, Number.isFinite(raw) ? raw : 0);
                        renderAll(true);
                    };
                    dec.addEventListener('click', () => { setItemQuantity(p, Math.max(0, q - Number(p.stepper || 1))); renderAll(true); });
                    inc.addEventListener('click', () => { setItemQuantity(p, q + Number(p.stepper || 1)); renderAll(true); });
                    inp.addEventListener('change', sync);
                    inp.addEventListener('blur', sync);
                    stepEl.appendChild(dec);
                    stepEl.appendChild(inp);
                    stepEl.appendChild(inc);
                    footer.appendChild(stepEl);
                }

                card.appendChild(cover);
                card.appendChild(body);
                card.appendChild(footer);
                booksRoot.appendChild(card);
            });
        });
    }

    function renderOrder() {
        const { cart } = cartSummary();
        const rows = Object.entries(cart.products || {})
            .map(([id, item]) => {
                const product = productById(id);
                if (!product) return null;
                const qty = Number(item.quantity || 0);
                if (qty <= 0) return null;
                return { product, qty, item };
            })
            .filter(Boolean);

        const buildRow = (targetEl) => {
            if (!targetEl) return;
            targetEl.innerHTML = '';
            if (rows.length <= 0) {
                const empty = document.createElement('p');
                empty.className = 'knm-muted knm-small';
                empty.textContent = 'No products in your order yet.';
                targetEl.appendChild(empty);
                return;
            }
            rows.forEach(({ product, qty }) => {
                const wrap = document.createElement('div');
                wrap.className = 'knm-order-row knm-card knm-order-line-pad';
                const main = document.createElement('div');
                main.className = 'knm-flex knm-gap-3';
                main.style.alignItems = 'flex-start';
                const imageHtml = product.image_url
                    ? `<div class="knm-order-row__media"><img src="${product.image_url}" alt="${product.name}" loading="lazy"></div>`
                    : `<div class="knm-order-row__media knm-order-row__media--placeholder" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                          <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                          <path d="M8 8h8M8 12h8M8 16h5"></path>
                        </svg>
                      </div>`;
                main.innerHTML = `${imageHtml}<div class="knm-grow"><div class="knm-book-heading">${product.name}</div>
                    <div class="knm-muted knm-small knm-mt-4">${product.class_name} · ${product.subject_name}</div>
                    <div class="knm-muted knm-small">Qty ${qty}</div></div>
                    <div class="knm-text-right"><div class="knm-book-heading">${money(Number(product.price || 0) * (qty / Number(product.stepper || 1)))}</div>
                    <button type="button" class="knm-btn knm-btn--sm knm-btn--ghost knm-mt-4 remove-line">Remove</button></div>`;
                wrap.appendChild(main);
                const remove = () => { setItemQuantity(product, 0); renderAll(true); renderOrder(); };
                main.querySelector('.remove-line').addEventListener('click', remove);
                attachSwipeRemove(wrap, remove);
                targetEl.appendChild(wrap);
            });
        };

        buildRow(orderItemsMobile);
        buildRow(orderItemsDesktop);
        updateCartUI();
    }

    function clearOrder() {
        setCart({ products: {} });
        renderAll(true);
        renderOrder();
        closeOrder();
    }

    function syncClassSelect() {
        if (!classSelect) return;
        classSelect.value = state.selectedClassId == null ? '' : String(state.selectedClassId);
    }

    function renderAll(shouldRenderOrder) {
        renderClassList(classListMobile, true);
        renderClassList(classListDesktop, false);
        renderBooks();
        syncClassSelect();
        updateCartUI();
        if (shouldRenderOrder) renderOrder();
    }

    classSelect?.addEventListener('change', () => {
        const v = classSelect.value;
        state.selectedClassId = v === '' ? null : Number(v);
        state.activeSubjectId = null;
        renderAll();
        if (state.selectedClassId != null) {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
    browseBooksBtn?.addEventListener('click', () => {
        const v = classSelect?.value || '';
        state.selectedClassId = v === '' ? null : Number(v);
        state.activeSubjectId = null;
        renderAll();
        if (state.selectedClassId != null) {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    menuButton?.addEventListener('click', openSidebar);
    sidebarBackdrop?.addEventListener('click', closeSidebar);
    sidebarClose?.addEventListener('click', closeSidebar);
    viewOrderMobile?.addEventListener('click', openOrder);
    cartButton?.addEventListener('click', openOrder);
    orderBackdrop?.addEventListener('click', closeOrder);
    orderClose?.addEventListener('click', closeOrder);
    clearOrderMobile?.addEventListener('click', clearOrder);
    clearOrderDesktop?.addEventListener('click', clearOrder);

    searchToggle?.addEventListener('click', () => {
        const open = searchPanel.classList.toggle('is-open');
        searchToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    renderAll(true);
})();
</script>
@endsection
