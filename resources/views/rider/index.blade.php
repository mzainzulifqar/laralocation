@extends('layouts.app')

@section('content')
    <div class="container-fluid">
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
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Route</div>
                    <div class="card">
                        <div id="map" style="height: 400px; width: 100%;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">

                <div class="card">
                    <div class="card-header">My Location</div>
                    <div class="card-body" id="address">
                    </div>
                </div>

                <div class="card mt-2">
                    <div class="card-header d-flex justify-content-between align-items-center">All Locations
                        <span>Distance From Rider</span>
                    </div>
                    <div class="card-body">
                        <ul class="list-group" id="all-locations">

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var map;
        var marker;
        var token = document.head.querySelector('meta[name="csrf-token"]');
        var tokenContent = token.content;

        function initMap() {
            var initialLocation = {
                lat: 33.6844,
                lng: 73.0479
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

                    fetchAddress(riderLocation.lat, riderLocation.lng);
                    // Get the locations and their distances from the rider
                    fetchRiderLocations(riderLocation);
                });
            }
        }

        function fetchRiderLocations(riderLocation) {
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

                var locationsList = document.getElementById('all-locations');
                data.locations.forEach(function(location) {
                    var locationItem =
                        '<li class="list-group-item d-flex justify-content-between align-items-center"><p style="width:60%;">' +
                        location.title +
                        '</p><div><span class="badge bg-primary">Distance: ' +
                        location.distance + ' km ' +
                        '</span> <a class="view-route" href="#" data-lat="' + location.latitude +
                        '" data-lng="' + location.longitude + '">View Route</a></div></li>';
                    locationsList.insertAdjacentHTML('beforeend', locationItem);
                });

                // Add click event listener to "View Route" links
                var viewRouteLinks = document.querySelectorAll('.view-route');
                viewRouteLinks.forEach(function(link) {
                    link.addEventListener('click', function(event) {
                        event.preventDefault();
                        var destinationLat = this.getAttribute('data-lat');
                        var destinationLng = this.getAttribute('data-lng');
                        fetchRoute(riderLocation, destinationLat, destinationLng);
                    });
                });

            }).catch(function(error) {
                console.log(error);
            });
        }

        // Get Rider Current Location
        function fetchAddress(latitude, longitude) {
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
                    document.getElementById('address').innerHTML = '<p class="alert alert-info">Location: ' + data
                        .address + '</p>';
                } else {
                    document.getElementById('address').innerHTML = '<p class="alert alert-warning">Not found</p';
                }
            }).catch(function(error) {
                console.log(error);
            });
        }

        function fetchRoute(origin, destinationLat, destinationLng) {

            // Get origin and destination
            var origin = origin.lat + ',' + origin.lng;
            var destination = destinationLat + ',' + destinationLng;

            // Create a DirectionsService object to use the route method and get a result
            var directionsService = new google.maps.DirectionsService();

            // Create a DirectionsRenderer object to display the route
            var directionsRenderer = new google.maps.DirectionsRenderer();

            // Bind the DirectionsRenderer to the map
            directionsRenderer.setMap(map);

            directionsService.route({
                origin: origin,
                destination: destination,
                travelMode: 'DRIVING'
            }, function(response, status) {
                if (status === 'OK') {
                    // Display the route
                    directionsRenderer.setDirections(response);

                    // Generate Google Maps link
                    var mapsLink = document.createElement('a');
                    mapsLink.href = 'https://www.google.com/maps/dir/?api=1&origin=' + encodeURIComponent(origin) +
                        '&destination=' + encodeURIComponent(destination);
                    mapsLink.textContent = 'Open in Google Maps';
                    mapsLink.target = '_blank';

                    // Append link to the document
                    document.body.appendChild(mapsLink);
                } else {
                    window.alert('Directions request failed due to ' + status);
                }
            });
        }
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.api_key') }}&callback=initMap&libraries=geometry"
        async defer></script>

@endsection
