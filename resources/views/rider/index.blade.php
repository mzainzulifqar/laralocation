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
                    <div class="card-header d-flex justify-content-between align-items-center">All Locations <span>Distance
                            From Rider</span></div>
                    <div class="card-body">
                        <ul class="list-group" id="all-locations">

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function initMap() {

            // Try to get the rider's location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var riderLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    fetchAddress(riderLocation.lat, riderLocation.lng);
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
                // data.locations.forEach(function(location) {
                //     var locationItem = document.getElementById('distance' + location.id);
                //     locationItem.innerHTML = location.title + ': ' + location.distance + ' km';
                // });
                var locationsList = document.getElementById('all-locations');
                data.locations.forEach(function(location) {
                    var locationItem =
                        '<li class="list-group-item d-flex justify-content-between align-items-center">' +
                        location.title +
                        '<div><span class="badge bg-primary">Distance: ' +
                        location.distance + ' km ' +
                        '</span> <a href="">View Route</a></div></li>';
                    locationsList.insertAdjacentHTML('beforeend', locationItem);
                });
                // document.body.appendChild(locationsList);
            }).catch(function(error) {
                console.log(error);
            });
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
                console.log(data);
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
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.api_key') }}&callback=initMap" async
        defer></script>

@endsection
