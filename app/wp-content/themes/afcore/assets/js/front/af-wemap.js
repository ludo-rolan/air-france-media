const visiter_endroit = document.getElementsByClassName("visitez_endroit");
const map = document.getElementsByClassName("carteadresse");

if (visiter_endroit.length > 0) {
    visiter_endroit[0].before(map[0]);
}

var container = document.getElementById('map-container');
var iframeEmbed = true;
var localisation = af.localisation;
var livemap = wemap.v1.createLivemap(container, {
    emmid: af.emmid,
    token: af.token
});


livemap.waitForReady().then(function () {
    selected_localisation = tryParseJSONObject(localisation.localisation_map);
  
        setTimeout(function () {
            if (selected_localisation) {
                console.log("map selected by user");
                console.log(Number(selected_localisation.latitude), Number(selected_localisation.longitude) ,Number(selected_localisation.zoom));
                livemap.centerTo({ latitude: Number(selected_localisation.latitude), longitude: Number(selected_localisation.longitude) }, Number(selected_localisation.zoom));
            }
            else {
                console.log("map selected by default");
                console.log(Number(localisation.localisation_latitude), Number(localisation.localisation_longitude) , 10);
                livemap.centerTo({ latitude: Number(localisation.localisation_latitude), longitude: Number(localisation.localisation_longitude) }, 10);

            }
        }, 1000);

    });