document.addEventListener("DOMContentLoaded", function () {
    let menuItem = document.querySelector(".menu-item"); 
    let submenu = document.querySelector(".submenu");

    if (menuItem && submenu) {
        menuItem.addEventListener("mouseenter", function () {
            submenu.style.display = "flex"; // Affichage en mode flex
        });

        menuItem.addEventListener("mouseleave", function () {
            submenu.style.display = "none"; // Masquer la sous-liste
        });
    } else {
        console.error("Erreur: Élément .menu-item ou .submenu introuvable !");
    }
});



function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(openGoogleMaps, showError);
    } else {
        alert("La géolocalisation n'est pas supportée par ce navigateur.");
    }
}

function openGoogleMaps(position) {
    let latitude = position.coords.latitude;
    let longitude = position.coords.longitude;
    
    // Générer l'URL Google Maps
    let mapsUrl = `https://www.google.com/maps?q=${latitude},${longitude}`;
    
    // Rediriger directement vers Google Maps
    window.location.href = mapsUrl;
}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            alert("L'utilisateur a refusé la demande de géolocalisation.");
            break;
        case error.POSITION_UNAVAILABLE:
            alert("Les informations de localisation ne sont pas disponibles.");
            break;
        case error.TIMEOUT:
            alert("La demande de localisation a expiré.");
            break;
        case error.UNKNOWN_ERROR:
            alert("Une erreur inconnue s'est produite.");
            break;
    }
}
