@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
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

                <div class="card">
                    <div class="card-header">{{ __('Rider Locations') }}</div>

                    <div class="card-body">
                        <ul>
                            @foreach ($locations as $location)
                                <li>{{ $location->address }}: {{ $location->latitude }}, {{ $location->longitude }}</li>
                            @endforeach
                        </ul>

                    </div>
                </div>

                <div class="card">
                    <div class="card-header"></div>
                    <div class="card-body">
                        <div id="map" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

            // Try to get the rider's location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var riderLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    map.setCenter(riderLocation);
                    marker = new google.maps.Marker({
                        position: riderLocation,
                        map: map,
                        draggable: true
                    });

                    // Get the locations and their distances from the rider
                    fetchRiderLocations(riderLocation);
                });
            }
        }

        function fetchRiderLocations(riderLocation) {
            var token = document.head.querySelector('meta[name="csrf-token"]');
            var tokenContent = token.content;
            var formData = new FormData();
            formData.append('latitude', riderLocation.lat);
            formData.append('longitude', riderLocation.lng);
            formData.append('_token', tokenContent);

            fetch('/rider/get-location', {
                method: 'POST',
                body: formData
            }).then(function(response) {
                return response.json();
            }).then(function(data) {
                // Show the locations and their distances in the HTML (You can update this part as per your UI design)
                var locationsList = document.createElement('ul');
                data.locations.forEach(function(location) {
                    var locationItem = document.createElement('li');
                    locationItem.innerText = location.title + ': ' + location.distance + ' km';
                    locationsList.appendChild(locationItem);
                });
                document.body.appendChild(locationsList);
            }).catch(function(error) {
                console.log(error);
            });
        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.api_key') }}&callback=initMap" async defer>
    </script>

@endsection
