const map = document.createElement('div');
const open_map_btn = document.createElement('div');
const localisation_bloc = document.getElementById("localisation");
let localisation = af.localisation;
if ('localisation_map' in localisation) {
    localisation_map = (JSON.stringify(localisation.localisation_map, undefined, 2));
    document.getElementById('localisation_map').value = localisation_map
    console.log(localisation);
}
else {
    localisation_map = -1;
    console.log('localisation_map est vide !');
}
//styler la map 

//Ajout ids au map et btn
map.setAttribute('id', 'map_container');
open_map_btn.setAttribute('id', 'open_map_btn');

open_map_btn.innerHTML = "Ouvrir Wemap"
open_map_btn.className = "button button-primary button-large";

//Ajouter map et btn au bloc localisation
localisation_bloc.appendChild(open_map_btn);
localisation_bloc.appendChild(map);
document.getElementById('localisation_map').style.display = "none";
document.getElementById("open_map_btn").onclick = function () {
    //checker si la map container est vide 
    if (map.childNodes.length === 0) {
        open_map_btn.innerHTML = "Wemap est en chargement ! "
        map.style.height = "600px";
        map.style.width = "800px"
        var livemap = wemap.v1.createLivemap(map, {
            emmid: af.emmid,
            token: af.token
        });
        livemap.waitForReady().then(function () {
            open_map_btn.innerHTML = "Attention! Changer la disposition de la map changera la disposition dans le front  "
            selected_localisation = tryParseJSONObject(localisation.localisation_map);
            setTimeout(function () {
                if (selected_localisation) {
                    console.log("map selected by user");
                    console.log(Number(selected_localisation.latitude), Number(selected_localisation.longitude), Number(selected_localisation.zoom));
                    livemap.centerTo({ latitude: Number(selected_localisation.latitude), longitude: Number(selected_localisation.longitude) }, Number(selected_localisation.zoom));
                }
                else if (localisation.length != 0) {
                    console.log(Number(localisation.localisation_latitude), Number(localisation.localisation_longitude), 10);
                    livemap.centerTo({ latitude: Number(localisation.localisation_latitude), longitude: Number(localisation.localisation_longitude) }, 10);
                }else{
                    livemap.centerTo({ latitude: 0, longitude: 0 }, 2);
                }
            }, 1000);
            livemap.addEventListener('livemapMoved', function (data) {
                document.getElementById('localisation_map').value = JSON.stringify(data)
                console.log(JSON.stringify(data, undefined, 2));

            });
        });
    }
};

// synchronisation

var divWemapPoi = document.getElementById('wemap_poi');
// si la wemap_poi n'existe pas dans la page, on ignore tt.
if (divWemapPoi != null) {
    // on fait le hiding des input crée par metabox
    document.getElementById('wemap_poi_Synchroniser').style.display = 'none';
    document.querySelector('[for="wemap_poi_Synchroniser"]').style.display = 'none';

    if (window.location.href.match('[?&]post=[0-9]+')) {
        // préparation de url de sync (si sync deja dans le param)
        let url = new URL(window.location.href);
        if (!window.location.href.includes('sync')) {
            url.searchParams.set('sync', 1);
        }
        var syncButtonHref = url;
        // création des elems, styling & l'ajout dans la page
        var syncButtonHtml = document.createElement("a");
        var syncNbHtml = document.createElement("p");
        syncNbHtml.style.cssText = 'text-align:center';
        syncButtonHtml.style.cssText = 'padding: 9px;background-color: red;color: white;border-radius: 19px;position: relative;bottom: 35px;left: 91px;text-align: center';
        syncButtonHtml.href = syncButtonHref;
        syncButtonContent = document.createTextNode('Synchroniser');
        syncNB = document.createTextNode("Avant de synchroniser l'adresse avec Wemap Veuillez vérifier les élémenets suivants : image d'adresse, localisation, le contenu de post & le status de post")
        syncButtonHtml.appendChild(syncButtonContent);
        syncNbHtml.appendChild(syncNB);
        divWemapPoi.appendChild(syncButtonHtml);
        divWemapPoi.appendChild(syncNbHtml);
    } else {
        // le cas d'un nouveau post
        var syncNbHtmlNew = document.createElement("p");
        syncNbHtmlNew.style.cssText = 'text-align:center';
        syncInfo = document.createTextNode("Vous ne pouvez pas synchroniser le post avec la Wemap lors de la création, veuillez ajouter les éléments nécessaires (image de l'adresse, localisation et contenu du post) et rafraîchir la page ! ");
        syncNbHtmlNew.appendChild(syncInfo);
        divWemapPoi.appendChild(syncNbHtmlNew);
    }
}