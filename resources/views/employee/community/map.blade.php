<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style>

<div id="viewCommunityMap{{$community->id}}" class="modal fade" tabindex="-1" aria-hidden="true" 
    aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    {{$community->english_name}} - Map
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class=" mb-4">
                        <h5 class="card-header"></h5>
                        <div class="">
                            <div class="leaflet-map" id="communityMap"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module">

    const layerControlVar = document.getElementById('communityMap');
    const community = {!! json_encode($community) !!};

    document.addEventListener('#communityMap', function() {
    const name = L.marker([community["latitude"], community["longitude"]]).bindPopup(community["english_name"]);
    const cities = L.layerGroup(name);

    const street = L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
        maxZoom: 18
    });
    
    const watercolor = L.tileLayer('http://tile.stamen.com/watercolor/{z}/{x}/{y}.jpg', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
        maxZoom: 18
    });

    const layerControl1 = L.map('layerControl1', {
        center: [32.2428238, 35.494258],
        zoom: 10,
        layers: [street, cities]
    });
    
    // Add base layers to the map
    const baseLayers = {
        "Street": street,
        "Watercolor": watercolor
    };
    L.control.layers(baseLayers).addTo(layerControl1);
    });

</script>