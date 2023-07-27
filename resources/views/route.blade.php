<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Route</title>
</head>

<body>
    <div id="map" style="height: 400px; width: 100%;"></div>

    <form id="routeForm">
        <input type="text" id="origin" placeholder="Enter origin">
        <input type="text" id="destination" placeholder="Enter destination">
        <input type="submit" value="Get Route">
    </form>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.api_key') }}&callback=initMap">
    </script>
    
    <script>
        function initMap() {
            // Create a new map
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 7,
                center: {lat: 33.6844, lng: 73.0479}  // Center at Islamabad, Pakistan
            });

            // Create a DirectionsService object to use the route method and get a result
            var directionsService = new google.maps.DirectionsService();

            // Create a DirectionsRenderer object to display the route
            var directionsRenderer = new google.maps.DirectionsRenderer();

            // Bind the DirectionsRenderer to the map
            directionsRenderer.setMap(map);

            // Capture the form submit event
            document.getElementById('routeForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // Get origin and destination
                var origin = document.getElementById('origin').value;
                var destination = document.getElementById('destination').value;

                // Request route from Directions API
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
                        mapsLink.href = 'https://www.google.com/maps/dir/?api=1&origin=' + encodeURIComponent(origin) + '&destination=' + encodeURIComponent(destination);
                        mapsLink.textContent = 'Open in Google Maps';
                        mapsLink.target = '_blank';

                        // Append link to the document
                        document.body.appendChild(mapsLink);
                    } else {
                        window.alert('Directions request failed due to ' + status);
                    }
                });
            });
        }
    </script>
</body>

</html>
