"use strict";

    // attente de la fin du chargement de la page
document.addEventListener("DOMContentLoaded", function () {

    // +++++ +++++ +++++ Carousel +++++ +++++ +++++
    let carousel = new Carousel(document.getElementById("carousel--container"), {
        slidesToScroll: 3,
        slideAuto: true 
    });

});