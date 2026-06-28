{{-- ══ TOPBAR / NAVBAR ══ --}}
<header class="topbar">

    <div class="topbar-left">
        {{-- Mobile hamburger --}}
        <button class="topbar-mobile-menu" onclick="openSidebarMobile()" title="Menu" aria-label="Buka menu">
            <i data-lucide="menu" style="width:18px;height:18px;"></i>
        </button>

        <div>
            <h2 id="page-title" class="topbar-page-title">Dashboard</h2>
            <div id="page-sub" class="topbar-page-sub">Rekrutmen PKL Batch 2025</div>
        </div>
    </div>

    <div class="topbar-right">
        <div class="topbar-live-badge hidden sm:flex">
            <span class="topbar-live-dot"></span>
            Live
        </div>
        <div class="topbar-clock">
            <span id="dash-clock">--:--:--</span>
        </div>
    </div>

</header>