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
                    <div class="card-header">My Location</div>

                    <div class="card-body">


                    </div>
                </div>

                <div class="card">
                    <div class="card-header">My Past Locations</div>

                    <div class="card-body">
                        <div class="list-group">
                            @forelse ($locations as $location)
                                <a href=":javascript" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $location->name }}</h5>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($location->created_at)->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $location->latitude }} , {{ $location->longitude }}</p>
                                </a>
                            @empty
                                No Locations
                            @endforelse
                        </div>

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

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.api_key') }}&callback=initMap" async
        defer></script>

@endsection
