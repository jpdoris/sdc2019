import ParentClass from './Parent.js';
import loadGoogleMapsApi from '../modules/load-google-maps-api';

class GettingHere extends ParentClass {
    constructor() {
        super();
        const moduleClass = '.content-getting-here';
        if (!super.runThisModule(moduleClass)) return;

        $('.hotels-slick-slider').slick({
            centerMode: true,
            slidesToShow: 2,
            variableWidth: true,
            slidesToScroll: 1,
            dots: true,
            arrows: false,
            infinite: true,
            swipeToSlide: true,
        });

        $(".hotels-section__change-view-btn").on('click', function(ev) {
            // Find active link and it's active target. Remove active-ness from both

            const $activeBtn = $(this).siblings('.active');
            $activeBtn.removeClass('active');
            $($activeBtn.data('target')).addClass('d-none');


            $(this).addClass('active');
            $($(this).data('target')).removeClass('d-none');

            if ($(this).data('target') == $('.hotels-section__map-view-btn').data('target')) {
                $('.hotels-section__nav-container').addClass('hotel-map-view-enabled');
            } else {
                $('.hotels-section__nav-container').removeClass('hotel-map-view-enabled');
            }

        });

        const conventionCenterLatLng = {
            lat: $(hotelsMap).data('lat'),
            lng: $(hotelsMap).data('lng')
        };
        const conventionCenterMapEl = document.getElementById('convention-center-map')
        const hotelsMapEl = document.getElementById('hotelsMap');

        let googleMapsOptions = {
            center: conventionCenterLatLng,
            zoom: 16,
            disableDefaultUI: true,
            zoomControl: true,
            styles: [{
                    "featureType": "all",
                    "elementType": "geometry",
                    "stylers": [{
                            "visibility": "simplified"
                        },
                        {
                            "hue": "#ff0000"
                        },
                        {
                            "weight": "1"
                        }
                    ]
                },
                {
                    "featureType": "all",
                    "elementType": "labels.text",
                    "stylers": [{
                        "visibility": "simplified"
                    }]
                },
                {
                    "featureType": "administrative",
                    "elementType": "labels.text.fill",
                    "stylers": [{
                        "color": "#444444"
                    }]
                },
                {
                    "featureType": "landscape",
                    "elementType": "all",
                    "stylers": [{
                        "color": "#f2f2f2"
                    }]
                },
                {
                    "featureType": "poi",
                    "elementType": "all",
                    "stylers": [{
                        "visibility": "off"
                    }]
                },
                {
                    "featureType": "road",
                    "elementType": "all",
                    "stylers": [{
                            "saturation": -100
                        },
                        {
                            "lightness": 45
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "all",
                    "stylers": [{
                        "visibility": "simplified"
                    }]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "labels.icon",
                    "stylers": [{
                        "visibility": "off"
                    }]
                },
                {
                    "featureType": "transit",
                    "elementType": "all",
                    "stylers": [{
                        "visibility": "off"
                    }]
                },
                {
                    "featureType": "water",
                    "elementType": "all",
                    "stylers": [{
                            "color": "#46bcec"
                        },
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry.fill",
                    "stylers": [{
                        "color": "#00b3e3"
                    }]
                }
            ]
        };
        const sdcMarkerIconUrl = '/wp-content/themes/sdc2019/images/map-marker-sdc-icon.png';
        const mapMarkerIconUrl = '/wp-content/themes/sdc2019/images/map-marker-icon.png';
        const mapMarkerIconSelectedUrl = '/wp-content/themes/sdc2019/images/map-marker-icon-selected.png';

        loadGoogleMapsApi({
            'key': 'AIzaSyA2ZRvHVXSnt0eD07GS2jJGol7fQDKtTt4',
        }).then(function(googleMaps) {
            const conventionCenterMap = new googleMaps.Map(conventionCenterMapEl, googleMapsOptions);

            new google.maps.Marker({
                position: conventionCenterLatLng,
                map: conventionCenterMap,
                title: 'SDC19',
                icon: sdcMarkerIconUrl
            });

            const hotelMapOptions = googleMapsOptions;
            hotelMapOptions['center'] = {
                lat: 37.3489612,
                lng: -121.8982565
            };
            hotelMapOptions['zoom'] = 13;
            const hotelsMap = new googleMaps.Map(hotelsMapEl, hotelMapOptions);

            // Conference marker
            new google.maps.Marker({
                position: conventionCenterLatLng,
                map: hotelsMap,
                title: 'SDC19',
                icon: sdcMarkerIconUrl
            });

            let markers = [];
            let infoWindows = [];
            $('#hotel-data__container .hotel-data').each(function(i, el) {
                markers.push(
                    new google.maps.Marker({
                        position: {
                            lat: $(el).data('lat'),
                            lng: $(el).data('lng'),
                        },
                        title: $(el).find('.hotel-data__name').first().text(),
                        map: hotelsMap,
                        icon: mapMarkerIconUrl
                    })
                );

                let hotelName = $(el).find('.hotel-data__name').first().get(0).outerHTML;
                let hotelDesc = $(el).find('.hotel-data__desc').first().get(0).outerHTML;

                infoWindows.push(
                    new google.maps.InfoWindow({
                        content: $("<div/>", {
                            class: 'infowindow-content-container',
                        }).append(hotelName).append(hotelDesc).get(0)
                    })
                );
            });
            markers.forEach(function(marker, i) {
                google.maps.event.addListener(marker, 'click', function() {
                    markers.forEach(function(el, index) {
                        el.setIcon(mapMarkerIconUrl)
                        infoWindows[index].close();
                    })

                    marker.setIcon(mapMarkerIconSelectedUrl)
                    infoWindows[i].open(hotelsMap, marker);
                })
            })
        }).catch(function(error) {
            console.error(error)
        })
    }
}

jQuery(document).ready(function() {
    new GettingHere();
});
