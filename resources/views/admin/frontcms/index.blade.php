@extends('admin.layouts.app')

@section('title', 'Front CMS')

@section('content')
    @push('styles')
    <style>
        /* ============================================
           CMSC Accounts Dashboard Style
           ============================================ */

        /* Navigation Buttons Row */
        .cms-nav-buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 16px;
        }

        @media (max-width: 992px) { .cms-nav-buttons { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 576px) { .cms-nav-buttons { grid-template-columns: 1fr; } }

        .cms-nav-btn {
            display: block;
            border: 2px solid #000;
            border-radius: 12px;
            background: #fff;
            min-height: 60px;
            box-shadow: 0 0 0 0 rgba(90, 113, 208, 0.11), 0 4px 16px 0 rgba(167, 175, 183, 0.33);
            transition: all .3s cubic-bezier(.25,.8,.25,1);
            cursor: pointer;
            position: relative;
            opacity: 0;
            animation: cmsBtnFadeIn 0.5s ease-out forwards;
        }

        .cms-nav-btn:nth-child(1) { animation-delay: 0.08s; }
        .cms-nav-btn:nth-child(2) { animation-delay: 0.16s; }
        .cms-nav-btn:nth-child(3) { animation-delay: 0.24s; }
        .cms-nav-btn:nth-child(4) { animation-delay: 0.32s; }

        @keyframes cmsBtnFadeIn {
            from { opacity: 0; transform: translateY(20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .cms-nav-btn a {
            color: #333;
            text-decoration: none;
            transition: all 0.3s linear;
            width: 100%;
            display: flex;
            align-items: center;
            padding: 10px 12px;
            gap: 12px;
        }

        .cms-nav-btn .btn-icon {
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            transition: filter 0.3s linear, transform 0.3s ease;
        }

        .cms-nav-btn .btn-label {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            color: #333;
            transition: color 0.3s linear;
        }

        .cms-nav-btn:hover {
            background: #24448d;
            border-color: #24448d;
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(36, 68, 141, 0.4);
        }

        .cms-nav-btn:hover .btn-label { color: #fff; }
        .cms-nav-btn:hover .btn-icon { filter: brightness(0) invert(1); transform: scale(1.1) rotate(-5deg); }
        .cms-nav-btn:active { transform: translateY(-1px) scale(0.98); transition: transform 0.1s ease; }

        /* ============================================
           Box / Panel Style (CMSC accounts dashboard)
           ============================================ */

        .cmsc-box {
            border: 1px solid #d4d4d4;
            border-radius: 0;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .cmsc-box-header {
            background: #2F5DA8;
            color: #fff;
            padding: 7px 10px;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cmsc-box-header .collapse-btn {
            background: none;
            border: none;
            color: #fff;
            cursor: pointer;
            font-size: 14px;
            padding: 0 4px;
        }

        .cmsc-box-body {
            padding: 10px;
        }

        /* Main Grid: left (chart area) + right (overview panel) */
        .cmsc-main-grid {
            display: grid;
            grid-template-columns: minmax(0, 7fr) minmax(220px, 3fr);
            gap: 0;
        }

        @media (max-width: 992px) {
            .cmsc-main-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Bottom Grid: two columns */
        .cmsc-bottom-grid {
            display: grid;
            grid-template-columns: minmax(0, 7fr) minmax(220px, 3fr);
            gap: 0;
            margin-top: 0;
        }

        @media (max-width: 992px) {
            .cmsc-bottom-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Stats summary line */
        .cmsc-stats-line {
            text-align: center;
            font-size: 13px;
            font-weight: 700;
            padding: 8px;
            color: #333;
        }

        .cmsc-stats-line span {
            margin: 0 8px;
        }

        /* Overview progress rows */
        .cmsc-overview-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        .cmsc-overview-row:last-child {
            border-bottom: none;
        }

        .cmsc-overview-row .label-text {
            font-weight: 600;
            color: #333;
        }

        .cmsc-overview-row .pct-text {
            font-weight: 700;
            min-width: 40px;
            text-align: right;
        }

        .cmsc-progress-bar {
            height: 4px;
            border-radius: 2px;
            margin-top: 4px;
        }

        /* Page/Post list items */
        .cmsc-list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 12px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 13px;
            transition: background 0.15s ease;
        }

        .cmsc-list-item:hover {
            background: #f8faff;
        }

        .cmsc-list-item:last-child {
            border-bottom: none;
        }

        .cmsc-list-item .item-name {
            font-weight: 600;
            color: #1f2937;
        }

        .cmsc-list-item .item-slug {
            font-size: 11px;
            color: #9ca3af;
        }

        .cmsc-empty {
            padding: 20px;
            text-align: center;
            color: #aaa;
            font-size: 13px;
        }

        /* Fade-in animation for panels */
        @keyframes cmscFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .cmsc-animate {
            opacity: 0;
            animation: cmscFadeIn 0.4s ease-out forwards;
        }

        .cmsc-animate-d1 { animation-delay: 0.1s; }
        .cmsc-animate-d2 { animation-delay: 0.2s; }
        .cmsc-animate-d3 { animation-delay: 0.3s; }
        .cmsc-animate-d4 { animation-delay: 0.4s; }
    </style>
    @endpush

    {{-- CMSC-style CMS Navigation Buttons --}}
    <div class="cms-nav-buttons">
        <div class="cms-nav-btn">
            <a href="{{ route('admin.hrms.dashboard', absolute: false) }}">
                <img src="{{ asset('assets/images/db/human-resources.png') }}" alt="HRMS" class="btn-icon" />
                <span class="btn-label">HRMS</span>
            </a>
        </div>
        <div class="cms-nav-btn">
            <a href="{{ route('admin.adm.dashboard', absolute: false) }}">
                <img src="{{ asset('assets/images/db/admin.png') }}" alt="Administration" class="btn-icon" />
                <span class="btn-label">Administration</span>
            </a>
        </div>
        <div class="cms-nav-btn">
            <a href="{{ route('admin.academics.dashboard', absolute: false) }}">
                <img src="{{ asset('assets/images/db/education.png') }}" alt="Academics" class="btn-icon" />
                <span class="btn-label">Academics</span>
            </a>
        </div>
        <div class="cms-nav-btn">
            <a href="{{ route('admin.account.accounts.dashboard', absolute: false) }}">
                <img src="{{ asset('assets/images/db/accounting.png') }}" alt="Accounts & Finance" class="btn-icon" />
                <span class="btn-label">Accounts & Finance</span>
            </a>
        </div>
    </div>

    {{-- Row 1: Pages List (left) + Pages Overview (right) --}}
    <div class="cmsc-main-grid cmsc-animate cmsc-animate-d1">
        <div class="cmsc-box">
            <div class="cmsc-box-header">
                <span>Pages - {{ date('M Y') }}</span>
                <button class="collapse-btn"><i class="fa fa-minus"></i></button>
            </div>
            <div class="cmsc-stats-line">
                TOTAL: {{ $pages->count() }} &nbsp;&nbsp; MEDIA FILES: {{ number_format($mediaCount) }}
            </div>
            <div class="cmsc-box-body" style="max-height: 280px; overflow-y: auto; padding: 0;">
                @forelse ($pages as $page)
                    <div class="cmsc-list-item">
                        <div>
                            <div class="item-name">{{ $page->title }}</div>
                            <div class="item-slug">{{ $page->slug }}</div>
                        </div>
                    </div>
                @empty
                    <div class="cmsc-empty">No CMS pages found.</div>
                @endforelse
            </div>
        </div>

        <div class="cmsc-box">
            <div class="cmsc-box-header">
                <span>Pages Overview</span>
            </div>
            <div class="cmsc-box-body" style="padding: 0;">
                <div class="cmsc-overview-row">
                    <span class="label-text">{{ $pages->count() }} TOTAL PAGES</span>
                    <span class="pct-text" style="color: #10b981;">100%</span>
                </div>
                <div style="padding: 0 12px;">
                    <div class="cmsc-progress-bar" style="background: #10b981; width: 100%;"></div>
                </div>

                <div class="cmsc-overview-row">
                    <span class="label-text">{{ number_format($mediaCount) }} MEDIA FILES</span>
                    <span class="pct-text" style="color: #f59e0b;">0%</span>
                </div>
                <div style="padding: 0 12px;">
                    <div class="cmsc-progress-bar" style="background: #f59e0b; width: 0%;"></div>
                </div>

                <div class="cmsc-overview-row">
                    <span class="label-text">0 PUBLISHED</span>
                    <span class="pct-text" style="color: #3b82f6;">0%</span>
                </div>
                <div style="padding: 0 12px;">
                    <div class="cmsc-progress-bar" style="background: #3b82f6; width: 0%;"></div>
                </div>

                <div class="cmsc-overview-row">
                    <span class="label-text">0 DRAFT</span>
                    <span class="pct-text" style="color: #ef4444;">0%</span>
                </div>
                <div style="padding: 0 12px;">
                    <div class="cmsc-progress-bar" style="background: #ef4444; width: 0%;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: Posts List (left) + Posts Overview (right) --}}
    <div class="cmsc-bottom-grid cmsc-animate cmsc-animate-d2">
        <div class="cmsc-box">
            <div class="cmsc-box-header">
                <span>Posts - {{ date('M Y') }}</span>
                <button class="collapse-btn"><i class="fa fa-minus"></i></button>
            </div>
            <div class="cmsc-stats-line">
                TOTAL: {{ $posts->count() }} &nbsp;&nbsp; TODAY: 0
            </div>
            <div class="cmsc-box-body" style="max-height: 280px; overflow-y: auto; padding: 0;">
                @forelse ($posts as $post)
                    <div class="cmsc-list-item">
                        <div>
                            <div class="item-name">{{ $post->title }}</div>
                            <div class="item-slug">{{ $post->slug }}</div>
                        </div>
                    </div>
                @empty
                    <div class="cmsc-empty">No CMS posts found.</div>
                @endforelse
            </div>
        </div>

        <div class="cmsc-box">
            <div class="cmsc-box-header">
                <span>Posts Overview - {{ date('M Y') }}</span>
                <button class="collapse-btn"><i class="fa fa-minus"></i></button>
            </div>
            <div class="cmsc-box-body" style="padding: 0;">
                <div class="cmsc-overview-row">
                    <span class="label-text">{{ $posts->count() }} TOTAL POSTS</span>
                    <span class="pct-text" style="color: #10b981;">100%</span>
                </div>
                <div style="padding: 0 12px;">
                    <div class="cmsc-progress-bar" style="background: #10b981; width: 100%;"></div>
                </div>

                <div class="cmsc-overview-row">
                    <span class="label-text">0 PUBLISHED</span>
                    <span class="pct-text" style="color: #3b82f6;">0%</span>
                </div>
                <div style="padding: 0 12px;">
                    <div class="cmsc-progress-bar" style="background: #3b82f6; width: 0%;"></div>
                </div>

                <div class="cmsc-overview-row">
                    <span class="label-text">0 DRAFT</span>
                    <span class="pct-text" style="color: #f59e0b;">0%</span>
                </div>
                <div style="padding: 0 12px;">
                    <div class="cmsc-progress-bar" style="background: #f59e0b; width: 0%;"></div>
                </div>

                <div class="cmsc-overview-row">
                    <span class="label-text">0 ARCHIVED</span>
                    <span class="pct-text" style="color: #ef4444;">0%</span>
                </div>
                <div style="padding: 0 12px;">
                    <div class="cmsc-progress-bar" style="background: #ef4444; width: 0%;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
