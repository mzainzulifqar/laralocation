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

                <div id="map" style="height: 400px;"></div>

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
                        '<li class="list-group-item d-flex justify-content-between align-items-center">' +
                        location.title +
                        '<div><span class="badge bg-primary">Distance: ' +
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
            var formData = new FormData();
            formData.append('origin', origin.lat + ',' + origin.lng);
            formData.append('destination', destinationLat + ',' + destinationLng);
            formData.append('_token', tokenContent);

            fetch('/rider/get-route', {
                method: 'POST',
                body: formData
            }).then(function(response) {
                return response.json();
            }).then(function(data) {
                // Display the route on the map
                displayRoute(data);
            }).catch(function(error) {
                console.log(error);
            });
        }

        function displayRoute(routeData) {
            // Check if the response contains routes
            console.log(routeData);
            if (routeData.routes && routeData.routes.length > 0) {
                var route = routeData.routes[0];

                // Check if the route contains the expected overview_polyline property
                if (route.overview_polyline && route.overview_polyline.points) {
                    var overviewPath = route.overview_polyline.points;
                    var decodedPath = google.maps.geometry.encoding.decodePath(overviewPath);

                    var routePolyline = new google.maps.Polyline({
                        path: decodedPath,
                        strokeColor: '#007BFF',
                        strokeOpacity: 1.0,
                        strokeWeight: 2
                    });
                    routePolyline.setMap(map);
                } else {
                    console.log('Route data is incomplete or missing overview_polyline property.');
                }
            } else {
                console.log('No routes found in the response.');
            }
        }
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.api_key') }}&callback=initMap&libraries=geometry"
        async defer></script>

@endsection
