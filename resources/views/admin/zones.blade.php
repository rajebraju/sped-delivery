<!DOCTYPE html>
<html>
<head>
    <title>Define Delivery Zones</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
    <style>#map { height: 600px; }</style>
</head>
<body>
<div id="map"></div>
<form id="zoneForm">
    @csrf
    <input type="hidden" name="restaurant_id" value="1">
    <input type="hidden" name="type" value="polygon">
    <textarea id="coordinates" name="coordinates" hidden></textarea>
    <button type="submit">Save Zone</button>
</form>

<script>
const map = L.map('map').setView([51.505, -0.09], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

const drawnItems = new L.FeatureGroup();
map.addLayer(drawnItems);

new L.Control.Draw({
    edit: { featureGroup: drawnItems },
    draw: { rectangle: false, marker: false, circle: false, polyline: false }
}).addTo(map);

map.on(L.Draw.Event.CREATED, (e) => {
    const layer = e.layer;
    drawnItems.addLayer(layer);
    const coords = layer.getLatLngs()[0].map(p => [p.lng, p.lat]);
    document.getElementById('coordinates').value = JSON.stringify(coords);
});

document.getElementById('zoneForm').onsubmit = async (e) => {
    e.preventDefault();
    const data = new FormData(e.target);
    const json = Object.fromEntries(data);
    await fetch('/api/admin/zones', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        body: JSON.stringify(json)
    }).then(r => r.json()).then(console.log);
};
</script>
</body>
</html>