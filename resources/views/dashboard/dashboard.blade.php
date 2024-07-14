@extends('layouts.presensi')
@section('content')
    <!-- App Capsule -->
    <div id="appCapsule">
        <div class="section" id="user-section">
            <div id="user-detail">
                <div class="avatar">
                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded">
                </div>
                <div id="user-info">
                    <h2 id="user-name">{{ $karyawan->nama_lengkap }}</h2>
                    <span id="user-role">{{ $karyawan->jabatan }}</span>
                </div>
                <!-- Tombol Exit -->
                <div href="/proseslogout" id="exit-button" style="margin-left: auto;">
                    <button class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <ion-icon name="exit-outline"></ion-icon>
                        Log Out
                    </button>
                </div>
                <form id="logout-form" action="/proseslogout" method="GET" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

        <div class="section" id="menu-section">
            <div class="card">
                <div class="card-body text-center">
                    <div class="list-menu">
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="green" style="font-size: 40px;">
                                    <ion-icon name="person-sharp"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Profil</span>
                            </div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="danger" style="font-size: 40px;">
                                    <ion-icon name="calendar-number"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Cuti</span>
                            </div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="warning" style="font-size: 40px;">
                                    <ion-icon name="document-text"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Histori</span>
                            </div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="orange" style="font-size: 40px;">
                                    <ion-icon name="location"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                Lokasi
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section mt-2" id="presence-section">
            <div class="todaypresence">
                <div class="row">
                    <div class="col-6">
                        <a href="/presensi/create">
                            <div class="card gradasigreen">
                                <div class="card-body">
                                    <div class="presencecontent">
                                        <div class="iconpresence">
                                            @if ($presensi_hari_ini != null)
                                                @php
                                                    $path = Storage::url(
                                                        'uploads/presensi/' . $presensi_hari_ini->foto_masuk,
                                                    );
                                                @endphp
                                                <img src="{{ url($path) }}" alt="" class="imaged w64">
                                            @else
                                                <ion-icon name="camera"></ion-icon>
                                            @endif
                                        </div>
                                        <div class="presencedetail">
                                            <h4 class="presencetitle">Masuk</h4>
                                            <span>{{ $presensi_hari_ini != null ? $presensi_hari_ini->jam_masuk : 'Belum Presensi' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                        <div class="card gradasired">
                            <div class="card-body">
                                <div class="presencecontent">
                                    <div class="iconpresence">
                                        @if ($presensi_hari_ini != null && $presensi_hari_ini->jam_keluar != null)
                                            @php
                                                $path = Storage::url(
                                                    'uploads/presensi/' . $presensi_hari_ini->foto_keluar,
                                                );
                                            @endphp
                                            <img src="{{ url($path) }}" alt="" class="imaged w64">
                                        @else
                                            <ion-icon name="camera"></ion-icon>
                                        @endif
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="presencetitle">Pulang</h4>
                                        <span>{{ $presensi_hari_ini != null && $presensi_hari_ini->jam_keluar ? $presensi_hari_ini->jam_keluar : 'Belum Presensi' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="presencetab mt-2">
                <div class="tab-pane fade show active" id="pilled" role="tabpanel">
                    <ul class="nav nav-tabs style1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                                Bulan Ini
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                                Leaderboard
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content mt-2" style="margin-bottom:100px;">
                    <div class="tab-pane fade show active" id="home" role="tabpanel">
                        <ul class="listview image-listview">
                            @foreach ($riwayat_bulan_ini as $d)
                                @php
                                    $path = Storage::url('uploads/presensi/' . $d->foto_masuk);
                                @endphp
                                <li>
                                    <div class="item">
                                        <div class="icon-box bg-primary">
                                            <ion-icon name="checkmark-circle-outline"></ion-icon>
                                        </div>
                                        <div class="in">
                                            <div>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</div>
                                            <span class="badge badge-success">{{ $d->jam_masuk }}</span>
                                            <span
                                                class="badge badge-danger">{{ $presensi_hari_ini != null && $d->jam_keluar != null ? $d->jam_keluar : 'Belum Presensi' }}</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    {{-- <div class="tab-pane fade" id="profile" role="tabpanel">
                        <ul class="listview image-listview">
                            <li>
                                <div class="item">
                                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                                    <div class="in">
                                        <div>Edward Lindgren</div>
                                        <span class="text-muted">Designer</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                                    <div class="in">
                                        <div>Emelda Scandroot</div>
                                        <span class="badge badge-primary">3</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                                    <div class="in">
                                        <div>Henry Bove</div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                                    <div class="in">
                                        <div>Henry Bove</div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                                    <div class="in">
                                        <div>Henry Bove</div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div> --}}

                </div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->
@endsection
