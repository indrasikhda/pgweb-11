<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- leaflet css link  -->
    <link rel="stylesheet"
      href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    />

    <title>Web-GIS with Geoserver and Leaflet</title>

    <style>
      body {
        margin: 0;
        padding: 0;
      }
      #map {
        width: 100%;
        height: 100vh;
      }
      .button-container {
        position: absolute;
        top: 170px; /* Positioned below the default Leaflet controls */
        left: 10px;
        z-index: 400; /* z-index below the geoportal overlay */
      }
      .custom-button {
        padding: 5px 10px;
        background-color: white;
        border: 2px solid #ccc;
        border-radius: 5px;
        cursor: pointer;
      }
      .custom-button:hover {
        background-color: #f4f4f4;
      }
      #geoportal-container {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1000;
        background: white;
      }
      #backBtn {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 1001;
      }
    </style>
  </head>

  <body>
    <div id="map"></div>

    <div id="geoportal-container">
        <button id="backBtn" class="custom-button">Back</button>
        <iframe src="https://geoportal.slemankab.go.id/datasets/geonode:kasus_dbd_2025_semester1/embed" style="width: 100%; height: 100%; border: none;"></iframe>
    </div>

    <div class="button-container">
        <button id="geoportalBtn" class="custom-button">Go to Geoportal</button>
    </div>

    <!-- leaflet js link  -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script>
      // ===============================
      // MAP DASAR
      // ===============================
      var map = L.map("map").setView([-7.732521, 110.402376], 11);

      var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: "© OpenStreetMap contributors",
      }).addTo(map);

      // ===============================
      // LAYER WMS DARI GEOSERVER
      // ===============================

      // 1. ADMINISTRASIDESA_AR_25K
      var desa = L.tileLayer.wms(
        "http://localhost:8080/geoserver/pgweb/wms",
        {
          layers: "pgweb:ADMINISTRASIDESA_AR_25K",
          format: "image/png",
          transparent: true
        }
      ).addTo(map);

      // 2. JALAN_LN_25K
      var jalan = L.tileLayer.wms(
        "http://localhost:8080/geoserver/pgweb/wms",
        {
          layers: "pgweb:JALAN_LN_25K",
          format: "image/png",
          transparent: true
        }
      ).addTo(map);

      // 3. data_kecamatan
      var kecamatan = L.tileLayer.wms(
        "http://localhost:8080/geoserver/pgweb/wms",
        {
          layers: "pgweb:data_kecamatan",
          format: "image/png",
          transparent: true
        }
      ).addTo(map);

      // 4. data toponimi
      var toponimi = L.tileLayer.wms(
        "http://localhost:8080/geoserver/pgweb/wms",
        {
          layers: "pgweb:TOPONIMI_PT_25K",
          format: "image/png",
          transparent: true
        }
      ).addTo(map);


      // ===============================
      // LAYER CONTROL
      // ===============================
      var overlayLayers = {
        "Administrasi Desa (AR_25K)": desa,
        "Jalan 25K": jalan,
        "Data Toponimi": toponimi
      };

      L.control.layers(null, overlayLayers, {position: 'topleft'}).addTo(map);

    </script>
    <script>
      document.getElementById('geoportalBtn').addEventListener('click', function() {
        document.getElementById('map').style.display = 'none';
        document.querySelector('.leaflet-control-container').style.display = 'none';
        document.getElementById('geoportal-container').style.display = 'block';
      });
      document.getElementById('backBtn').addEventListener('click', function() {
        document.getElementById('map').style.display = 'block';
        document.querySelector('.leaflet-control-container').style.display = 'block';
        document.getElementById('geoportal-container').style.display = 'none';
        map.invalidateSize();
      });
    </script>
  </body>
</html>