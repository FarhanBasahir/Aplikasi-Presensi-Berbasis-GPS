@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="/" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Presensi Berbasis GPS</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
    <style>
        .webcam-capture,
        .webcam-capture video {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;
        }

        #map {
            height: 200px;
        }
    </style>

    {{-- CSS Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    {{-- JS Leaflet --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection

@section('content')
    <!-- App Capsule -->
    <div class="row" style="margin-top: 70px;">
        <div class="col">
            <input type="hidden" id="lokasi" style="margin : 5px;">
            <div class="webcam-capture"></div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            @if ($cek > 0)
                <button id="takePresensi" class="btn btn-danger btn-block">
                    <ion-icon name="camera-outline"></ion-icon>
                    Presensi Pulang
                </button>
            @else
                <button id="takePresensi" class="btn btn-primary btn-block">
                    <ion-icon name="camera-outline"></ion-icon>
                    Presensi Masuk
                </button>
            @endif
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <div id="map"></div>
        </div>
    </div>
    <!-- * App Capsule -->
@endsection

@push('myscript')
    <script>
        Webcam.set({
            height: 460,
            width: 640,
            image_format: 'jpeg',
            jpeg_quality: 80
        });

        Webcam.attach('.webcam-capture');

        var lokasi = document.getElementById('lokasi');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        }

        function successCallback(position) {
            // deklarasi value lokasi
            lokasi.value = position.coords.latitude + "," + position.coords.longitude;

            // Menampilkan map
            var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 16);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Menampilkan marker posisi lokasi terkini
            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);

            //Membuat Radius Kantor Poliban
            var circle = L.circle([-3.296579, 114.582140], {

                // radius contoh
                // var circle = L.circle([-3.291274, 114.590917], {
                color: 'blue',
                fillColor: 'blue',
                fillOpacity: 0.5,
                radius: 50
            }).addTo(map);
        }

        function errorCallback(error) {
            console.error(error);
            alert("Geolocation is not supported by this browser or permission denied.");
        }

        // function untuk mengambil data presensi
        $("#takePresensi").click(function(e) {
            e.preventDefault(); // Prevent form submission

            // mengambil data gambar  
            var image = "";
            Webcam.snap(function(uri) {
                image = uri;
            });

            // mengambil data lokasi
            var lokasi = $("#lokasi").val();
            $.ajax({
                type: 'POST',
                url: '/presensi/store',
                data: {
                    _token: "{{ csrf_token() }}",
                    image: image,
                    lokasi: lokasi
                },
                cache: false,
                success: function(respond) {
                    var status = respond.split("|");
                    if (status[0] == "success") {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: status[1],
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        setTimeout(function() {
                            location.href = '/dashboard';
                        }, 2000);
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: status[1],
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        });
    </script>
@endpush
