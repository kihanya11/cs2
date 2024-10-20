<div>
<div>
    <h1>Google Maps Integration</h1>
    <div id="map" style="width: 100%; height: 500px;"></div>

    <!-- Load the Google Maps JavaScript API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAdcSrt_L4nlIaUemDt2w24kTY3G5J9zt0"></script>
    <script>
        // Initialize and add the map
        function initMap() {
            // The location (latitude and longitude)
            const location = { lat: -34.397, lng: 150.644 }; 
            
            // The map, centered at the location
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 8,
                center: location,
            });

            // The marker, positioned at the location
            const marker = new google.maps.Marker({
                position: location,
                map: map,
            });
        }

        // Call the function to initialize the map when the window loads
        window.onload = initMap;
    </script>
</div>
</div>
