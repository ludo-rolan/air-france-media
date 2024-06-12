
   /* Load remote Batch SDK JavaScript code */
(function(b,a,t,c,h,e,r){h='batchSDK';b[h]=b[h]||function() {
    (b[h].q=b[h].q||[]).push(arguments)};e=a.createElement(t),r=a.getElementsByTagName(t)[0];
    e.async=1;e.src=c;r.parentNode.insertBefore(e,r);})(window,document,'script','https://via.batch.com/v3/bootstrap.min.js');
    preprod = batchLoaderArgs.preprod;
    if(preprod == 'true'){preprod = true} else preprod =false;
   
/* Initiate Batch SDK opt-in UI configuration (native prompt) */
var batchSDKUIConfig = {
    native: {}
};

/* Use a specific configuration for Firefox and Safari browsers (custom prompt) */
if (navigator.userAgent.indexOf("Firefox") !== -1 || (navigator.userAgent.indexOf("Safari") !== -1 &&
navigator.userAgent.indexOf("Chrome") === -1)) {
    batchSDKUIConfig = {
        alert: {
            icon: 'https://icons.batch.com/EE43C8713718430ABC4FAB435B7EBADB/default-icon.png', // logo couleur 192x192 qui sera utilisé pour l'alerte sur Firefox
            text: "Recevez en temps réel nos dernières actualités !",
            positiveSubBtnLabel: "Autoriser",
            negativeBtnLabel: "Plus tard",
            positiveBtnStyle: {
                  backgroundColor: "#ff2600", // couleur bouton
                  hoverBackgroundColor: " #912184", // couleur bouton au survol par la souris
                  textColor: "white" // couleur texte du bouton
              }
        }
    }
}


/* Finalize the Batch SDK setup */
batchSDK('setup', {
    apiKey: 'EE43C8713718430ABC4FAB435B7EBADB',
    subdomain: 'en-vols',
    authKey: '2.GukXq80VoEPXmqskazdUe1gijeUgGD5zy7RD5pFZgR4=',
    dev: preprod, // passez à false en production
    vapidPublicKey: 'BMPo2qngFBMpQrtqYRigs7135a6Jnta9nllbWJE62vIW84xOBjNQl6cIUPR4Qg9Nit+MVc2UjC7SkccGcLZRgNQ=',
    ui: batchSDKUIConfig,});

batchSDK(function (api) {
    api.setUserLanguage(batchLoaderArgs.langue); // Remplacer LANGUAGE
    api.setUserRegion(batchLoaderArgs.pays); // Remplacer REGIONg
    
});