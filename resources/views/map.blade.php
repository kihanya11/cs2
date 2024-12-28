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

                    <!-- Display Coordinates -->
                    <div id="coordinates" class="mt-4">
                        Click on the map to view GPS coordinates.
                    </div>

                    <!-- Weather Button -->
                    <button id="getWeatherBtn" class="weather-button mt-4">
                        Get Weather Data
                    </button>

                    <!-- Weather Data Table -->
                    <table class="mt-4 border-collapse w-full">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2">Temperature</th>
                                <th class="border px-4 py-2">Humidity</th>
                                <th class="border px-4 py-2">Wind Speed</th>
                                <th class="border px-4 py-2">Cloud Coverage</th>
                                <th class="border px-4 py-2">Solar Radiation (W/m²)</th> <!-- New column for Solar Radiation -->
                            </tr>
                        </thead>
                        <tbody id="weatherDataTable">
                            <!-- Weather Data will be inserted here -->
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <!-- Include the Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAdcSrt_L4nlIaUemDt2w24kTY3G5J9zt0&callback=initMap" async defer></script>

    <script>
        let clickedLat = null;
        let clickedLng = null;
        let map;
        let marker = null;

        function initMap() {
            const mapOptions = {
                center: { lat: -1.286389, lng: 36.817223 },
                zoom: 12
            };

            map = new google.maps.Map(document.getElementById("map"), mapOptions);

            // Add click listener to the map to capture the coordinates and add the pin (marker)
            map.addListener("click", (e) => {
                clickedLat = e.latLng.lat();
                clickedLng = e.latLng.lng();

                // Show the coordinates in the div
                document.getElementById("coordinates").textContent =
                    "Latitude: " + clickedLat + ", Longitude: " + clickedLng;

                // Remove the previous marker if it exists
                if (marker) {
                    marker.setMap(null);
                }

                // Add a new marker at the clicked location
                marker = new google.maps.Marker({
                    position: e.latLng,
                    map: map,
                    title: "Selected Location"
                });
            });

            // Event listener for the "Get Weather" button
            document.getElementById("getWeatherBtn").addEventListener("click", function() {
                if (clickedLat && clickedLng) {
                    fetchWeatherData(clickedLat, clickedLng);
                } else {
                    alert("Please click on the map to select a location first.");
                }
            });
        }

    // Fetch weather data based on selected coordinates
    function fetchWeatherData(lat, lng) {
    fetch(`/fetch-weather?lat=${lat}&lng=${lng}`)
        .then(response => response.json())
        .then(data => {
            if (data.cod !== 200) {
                // Handle error if response code isn't 200 (successful)
                alert("Error: " + (data.message || "Weather data not available."));
                return;
            }

            // Example of how to insert data into the table
            const weatherTable = document.getElementById("weatherDataTable");
            weatherTable.innerHTML = ` 
                <tr>
                    <td class="border px-4 py-2">${data.main.temp} °C</td>
                    <td class="border px-4 py-2">${data.main.humidity} %</td>
                    <td class="border px-4 py-2">${data.wind.speed} m/s</td>
                    <td class="border px-4 py-2">${data.clouds.all} %</td>
                    <td class="border px-4 py-2">${data.solar_radiation.toFixed(2)} W/m²</td>
                </tr>
            `;
        })
        .catch(error => {
            console.error("Error in fetch:", error); // Debug
        });
}


    </script>
</x-app-layout>
