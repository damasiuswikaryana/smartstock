<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<style>
    #{{ $mapId }} {
        @if (!isset($attributes['style']))
            height: 25vh;
        @else
            {{ $attributes['style'] }}
        @endif
    }
</style>

<div style="border-radius: 8px;" id="{{ $mapId }}"
    @if (isset($attributes['class'])) class='{{ $attributes['class'] }}' @endif></div>

<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    var mymap = L.map('{{ $mapId }}').setView([{{ $centerPoint['lat'] ?? $centerPoint[0] }},
        {{ $centerPoint['long'] ?? $centerPoint[1] }}
    ], {{ $zoomLevel }});
    @foreach ($markers as $marker)
        @if (isset($marker['icon']))
            var icon = L.icon({
                iconUrl: '{{ $marker['icon'] }}',
                iconSize: [{{ $marker['iconSizeX'] ?? 32 }}, {{ $marker['iconSizeY'] ?? 32 }}],
            });
        @endif
        var marker = L.marker([{{ $marker['lat'] ?? $marker[0] }}, {{ $marker['long'] ?? $marker[1] }}]
            @if (isset($marker['icon']))
                , {
                    icon: icon
                }
            @endif
        );
        marker.addTo(mymap);
        @if (isset($marker['info']))
            marker.bindPopup(@json($marker['info']));
        @endif
    @endforeach

    @if ($tileHost === 'mapbox')
        let url{{ $mapId }} =
            'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={{ config('maps.mapbox.access_token', null) }}';
    @elseif ($tileHost === 'openstreetmap')
        let url{{ $mapId }} = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    @else
        let url{{ $mapId }} = '{{ $tileHost }}';
    @endif
    L.tileLayer(url{{ $mapId }}, {
        maxZoom: {{ $maxZoomLevel }},
        attribution: '{!! $attribution !!}',
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1
    }).addTo(mymap);
</script>
