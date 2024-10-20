<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Map View') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Google Map -->
                    <div id="map" style="height: 500px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include the Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAdcSrt_L4nlIaUemDt2w24kTY3G5J9zt0&callback=initMap" async defer></script>
    <script>
        function initMap() {
            // Default map options
            const mapOptions = {
                center: { lat: -1.286389, lng: 36.817223 }, // Nairobi coordinates
                zoom: 12
            };

            // Create map instance
            const map = new google.maps.Map(document.getElementById("map"), mapOptions);

            // Add a click listener to capture click events
            map.addListener("click", (e) => {
                const latLng = e.latLng; // Get clicked location (lat, lng)

                // Display coordinates in the div
                document.getElementById("coordinates").textContent =
                    "Latitude: " + latLng.lat() + ", Longitude: " + latLng.lng();

                // Add marker at clicked location
                new google.maps.Marker({
                    position: latLng,
                    map: map,
                    title: "Selected Location"
                });
            });
        }

        // Call initMap after the window is fully loaded
        window.addEventListener('load', () => {
            initMap();
        });
    </script>
</x-app-layout>
