@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-warning">{{ session('error') }}</div>
                        @endif

                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-warning">{{ $error }}</div>
                            @endforeach
                        @endif

                        <div id="map" style="height: 400px;"></div>

                        <form action="{{ route('location.store') }}"
                        class="row row-cols-lg-auto g-3 align-items-center"
                        method="POST">
                            @csrf
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                            <div class="col-12">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" name="address" id="address" class="form-control"
                                    placeholder="Selected Location Address" readonly>
                            </div>
                            <div class="col-12 align-self-end">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                        <script>
                            var map;
                            var marker;

                            function initMap() {
                                var initialLocation = {
                                    lat: 0,
                                    lng: 0
                                };
                                map = new google.maps.Map(document.getElementById('map'), {
                                    center: initialLocation,
                                    zoom: 15
                                });

                                // Try to get the user's location
                                if (navigator.geolocation) {
                                    navigator.geolocation.getCurrentPosition(function(position) {
                                        var userLocation = {
                                            lat: position.coords.latitude,
                                            lng: position.coords.longitude
                                        };
                                        map.setCenter(userLocation);
                                        marker = new google.maps.Marker({
                                            position: userLocation,
                                            map: map,
                                            draggable: true
                                        });
                                        updateLocationFields(marker.getPosition()); // Pass the LatLng object here
                                    });
                                }

                                google.maps.event.addListener(map, 'click', function(event) {
                                    var clickedLocation = event.latLng;
                                    if (marker && marker.setMap) {
                                        marker.setMap(null);
                                    }
                                    marker = new google.maps.Marker({
                                        position: clickedLocation,
                                        map: map,
                                        draggable: true
                                    });
                                    updateLocationFields(clickedLocation); // Pass the LatLng object here
                                });

                                function updateLocationFields(location) {
                                    document.getElementById('latitude').value = location.lat();
                                    document.getElementById('longitude').value = location.lng();

                                    // Fetch address from backend
                                    fetchAddress(location.lat(), location.lng());
                                }

                                function fetchAddress(latitude, longitude) {
                                    var token = document.head.querySelector('meta[name="csrf-token"]');
                                    var tokenContent = token.content;
                                    var formData = new FormData();
                                    formData.append('latitude', latitude);
                                    formData.append('longitude', longitude);
                                    formData.append('_token', tokenContent);

                                    fetch('/location/fetch-address', {
                                        method: 'POST',
                                        body: formData
                                    }).then(function(response) {
                                        return response.json();
                                    }).then(function(data) {
                                        if (data && data.address) {
                                            document.getElementById('address').value = data.address;
                                        } else {
                                            document.getElementById('address').value = 'Not found';
                                        }
                                    }).catch(function(error) {
                                        console.log(error);
                                    });
                                }
                            }
                        </script>

                        <script
                            src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.api_key') }}&libraries=places&callback=initMap"
                            async defer></script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
