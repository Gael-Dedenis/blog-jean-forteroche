"use strict";

class Carousel {

    options = {};
    currentIndex = 0;
    interval = false;
    element = false;
    items = [];

    /**
     * 
     * @param {HTMLElement} element 
     * @param {Object} options 
     * @param {Object} options.slidesToScroll Nombre d"éléments composant le carrousel
     * @param {boolean} [options.slideAuto = true] Défilement automatique 
     * 
     */

    constructor(element, options = {}) {

        this.element = element;

        this.setOptions( options );
        this.setCarouselUi();
        this.setStyle();
        this.setCarouselEvents();

        this.autoStart();
    }

    /**
     * @param {string} className 
     * @returns {HTMLElement}
     */
    createElmtWithClass(className) {
        let div = document.createElement("div");
        div.setAttribute("class", className);
        return div;
    }

    setOptions(options) {
        this.options = Object.assign({}, {
            slidesToScroll: 3,
            slideAuto: true
        }, options)
    }

    // Modif DOM
    setCarouselUi() {

        let numberSlide = [].slice.call(this.element.children); // Création d"un tableau avec le nbr d"enfants
        this.root       = this.createElmtWithClass("carousel"); // Conteneur du bloc mobile
        this.panorama   = this.createElmtWithClass("carousel__panorama"); // Création du bloc "mobile" du carrousel

        this.root.setAttribute("tabcurrentIndex", "0");
        this.root.appendChild(this.panorama);
        this.element.appendChild(this.root);

        this.items = numberSlide.map((slide) => {
            let item = this.createElmtWithClass("carousel__item");
            item.appendChild(slide);
            this.panorama.appendChild(item);
            return item;
        }); 
    }

    //Applique les dimensions aux différents éléments du carrousel
    setStyle() {
        let ratio = this.items.length * 100;
        this.panorama.style.width = ratio + "%";
        this.items.forEach(item => item.style.width = (100 / this.items.length) + "%");
    }

    //Définitions des boutons de navigations du slider
    setCarouselEvents() {
        // bouton next et prev
        let nextButton      = document.getElementById("controls__next");
        let previousButton  = document.getElementById("controls__previous");

        nextButton.addEventListener("click", this.goNext.bind(this));
        previousButton.addEventListener("click", this.goPrevious.bind(this));

        // bouton Play & Pause
        let playButton = document.getElementById("controls__play");
        let stopButton = document.getElementById("controls__stop");

        playButton.addEventListener("click", this.play.bind(this));
        stopButton.addEventListener("click", this.stop.bind(this));

        // Evenements sur le carrousel
        document.addEventListener("keyup", this.keyControl.bind(this));
    }

    goNext() {
        this.gotoSlide(this.currentIndex += 1);
    }

    goPrevious() {
        this.gotoSlide(this.currentIndex -= 1);
    }

    //Déplace le carrousel vers l"élément ciblé.
    gotoSlide() {
        if(this.currentIndex < 0) {
            this.currentIndex = this.items.length - 1;
        } else if(this.currentIndex >= this.items.length) {
            this.currentIndex = 0;
        }
        let translateX = this.currentIndex * -100 / this.items.length;
        this.panorama.style.transform = "translate3d(" + translateX + "%, 0, 0)";
    }

    // controls au clavier
    keyControl = function(evt) {
        switch(evt.code) {
            case "ArrowRight":
                this.goNext();
                break;
            case "ArrowLeft":
                this.goPrevious();
                break;
            case "Space":
                this.stop();
                break;
            case "Enter":
                this.play();
                break;
        }
    }

    // slide automatiquement
    autoStart() {
        if ( this.options.slideAuto ){
            this.play();
        }
    }

    // play et stop
    play() {
        if(!this.interval) {
            this.interval = setInterval(this.goNext.bind(this), 5000);
        }
    }

    stop() {
        if(this.interval) {
            clearInterval(this.interval);
            this.interval = false;
        }
    }
}