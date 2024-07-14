<!-- App Bottom Menu -->
<div class="appBottomMenu">
    <a href="/dashboard" class="item {{ request()->is('dashboard') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="home-outline"role="img" class="md hydrated"
                aria-label="file tray full outline"></ion-icon>
            <strong>Beranda</strong>
        </div>
    </a>
    <a href="/calendar" class="item {{ request()->is('calendar') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="calendar-outline" role="img" class="md hydrated"
                aria-label="calendar outline"></ion-icon>
            <strong>Kalender</strong>
        </div>
    </a>
    <a href="/presensi/create" class="item {{ request()->is('create') ? 'active' : '' }}">
        <div class="col">
            <div class="action-button large">
                <ion-icon name="camera" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
            </div>
        </div>
    </a>
    <a href="/docs" class="item {{ request()->is('docs') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="document-text-outline" role="img" class="md hydrated"
                aria-label="document text outline"></ion-icon>
            <strong>Dokumen</strong>
        </div>
    </a>
    <a href="javascript:;" class="item {{ request()->is('profile') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="person-circle-outline" role="img" class="md hydrated" aria-label="people outline"></ion-icon>
            <strong>Profil</strong>
        </div>
    </a>
</div>
<!-- * App Bottom Menu -->
