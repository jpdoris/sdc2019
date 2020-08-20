import ParentClass from './Parent.js';
var moment = require('moment');

class Schedule extends ParentClass {
    constructor() {
        super();
        const moduleClass = '.content-schedule';
        if (!super.runThisModule(moduleClass)) return;


        $("#speakerModal").on('hidden.bs.modal', function(e) {
            // If there are modals still visible, then add modal open to body
            $("#sessionModal").removeClass('d-none');
            if ($(".modal:visible").length > 0) {
                $('body').addClass('modal-open');
            }
        });
        $("#speakerModal").on('shown.bs.modal', function(e) {
            $('.modal-backdrop:last').addClass('d-none');
            $("#sessionModal").addClass('d-none');
        });

        $("#speakerModal").on('click', 'button.close', function(e) {
            $("#sessionModal").modal('hide').removeClass('d-none');
        });

        this.filters = {
            topics: [],
            search: "",
            type: [],
            level: [],
            day: [], /* '10/29/2019','10/30/2019' */
        };

        this.getTopics();
        this.getTypes();
        this.getLevels();
        this.selectTopic();
        this.showHideFilters();
        this.clearFilters();

        // If there's a session ID in the URL, then grab it via window.location.pathname and regex
        // pass it to testing()
        // this.preloadedPresenterData = null;

        // this.sessionModalTesting(1089245);
        //
        // this.speakerModalTesting(821734);
    }

    getTopics() {
        const $container = $('.filter-topics-content');
        $.ajax({
            type: 'POST',
            url:  '/wp-content/plugins/sdc2019-harvester-integration/inc/Fetch.php',
            data : jQuery.param({
                'action' : 'listTopics',
            }),
            beforeSend: function() {
                $container.html('Fetching data...');
            },
            success: function(result) {
                let data = $.parseJSON(result);
                $container.html('');
                $.each(data, function(k, v) {
                    $container.append('<button>' + v + '</button>');
                });
            },
            error: function(xhr) {
                $container.html('Error retrieving data: ' + xhr.statusText + xhr.responseText);
            }
        });

        this.applyFilters();
    }

    getTypes() {
        const $container = $('#select-type');
        $.ajax({
            type: 'POST',
            url:  '/wp-content/plugins/sdc2019-harvester-integration/inc/Fetch.php',
            data : jQuery.param({
                'action' : 'listTypes',
            }),
            success: function(result) {
                let data = $.parseJSON(result);
                $.each(data, function(k, v) {
                    $container.append('<option value="' + k + '">' + v + '</option>');
                });
            },
            error: function(xhr) {
                $container.html('Error retrieving data: ' + xhr.statusText + xhr.responseText);
            }
        });
    }

    getLevels() {
        const $container = $('#select-level');
        $.ajax({
            type: 'POST',
            url:  '/wp-content/plugins/sdc2019-harvester-integration/inc/Fetch.php',
            data : jQuery.param({
                'action' : 'listLevels',
            }),
            success: function(result) {
                let data = $.parseJSON(result);
                $.each(data, function(k, v) {
                    $container.append('<option value="' + k + '">' + v + '</option>');
                });
            },
            error: function(xhr) {
                $container.html('Error retrieving data: ' + xhr.statusText + xhr.responseText);
            }
        });
    }

    selectTopic() {
        const self = this;

        $('.filter-topics').find('.filter-selected').each(function() {
             self.filters.topics.extend(e.target.innerText);
        });

        $('.filter-topics').on( "click", "button", function(e) {

            const $filtervalue = e.target.innerText;

            if ($(this).hasClass('filter-selected')) {
                // this was already selected, so we're removing:
                self.filters.topics = $.grep(self.filters.topics, function(value) {
                    return value !== $filtervalue;
                });
                $(this).removeClass('filter-selected');
                $(this).removeClass('remove-filter');
                $(this).addClass('add-filter');
            } else {
                // this was not selected before, so we're adding here:
                self.filters.topics.push($filtervalue);
                $(this).addClass('filter-selected');
                $(this).addClass('remove-filter');
                $(this).removeClass('add-filter');
            }

            self.applyFilters();
        });
    }

    applyFilters($resultlimit = 7) {
        const $container = $('.show-sessions');
        let $resultcount = 0;
        const self = this;
        $.ajax({
            type: 'POST',
            url:  '/wp-content/plugins/sdc2019-harvester-integration/inc/Fetch.php',
            data : jQuery.param({
                'action' : 'applyFilters',
                'filters' : JSON.stringify(self.filters),
            }),
            beforeSend: function() {
                $container.html('Fetching data...');
            },
            success: function(data) {
                let result = $.parseJSON(data);
                $resultcount = Object.keys(result).length;
                $container.html('');
                let i = 1;
                $.each(result, function(k, v) {
                    if (i <= $resultlimit) {
                        let hour = parseInt(v['PresentationTimeStart'].match( /^\d{2}/gm), 10);
                        let min = v['PresentationTimeStart'].match( /\:\d{2}/);
                        let suffix = hour >= 12 ? " PM" : " AM";
                        let formattedtime = (hour > 12 ? hour - 12 : hour) + min + suffix;
                        $container.append('<div class="row session-row" data-row="'+ k + '"><div class="col-3 session-time">' + formattedtime + '</div>' +
                            '<div class="col-9 session-name">' + v['SessionName'] + '</div></div>'
                        );
                    }
                    i++;
                });

                if ($resultcount > $resultlimit) {
                    $('.view-more').css('display', 'block');
                } else {
                    $('.view-more').css('display', 'none');
                }
            },
            error: function(xhr) {
                $container.html('Error retrieving data: ' + xhr.statusText + xhr.responseText);
            }
        });

        $('#view-more').on('click', function() {
            self.applyFilters($resultlimit + 7);
        });
    }

    showHideFilters() {
        $('#show-hide').on('click', function() {
            $('.filter-topics').toggleClass('hidden');
            $('.session-search').toggleClass('hidden');
            if ($('.filter-topics').hasClass('hidden')) {
                $(this).html('Show Filters');
            } else {
                $(this).html('Hide Filters');
            }
        });
    }

    clearFilters() {
        const self = this;

        $('.clear-all').on('click', function() {
            $('.filter-topics').find('.filter-selected').each(function() {
                $(this).removeClass('filter-selected');
                $(this).removeClass('remove-filter');
                $(this).addClass('add-filter');
            });
            self.applyFilters();
        });
    }

    getSessionByTitle() {
        const $container = $('.modal-body');
        $.ajax({
            type: 'POST',
            url:  '/wp-content/plugins/sdc2019-harvester-integration/inc/Fetch.php',
            data : jQuery.param({
                'action' : 'getSessionByTitle',
                'title' : this.filters,
            }),
            beforeSend: function() {
                $container.html('Fetching data...');
            },
            success: function(result) {
                let data = $.parseJSON(result);
                $container.html('');
            },
            error: function(xhr) {
                $container.html('Error retrieving data: ' + xhr.statusText + xhr.responseText);
            }
        });
    }

    preloadPresenterData(data) {
        this.preloadedPresenterData = data;
    }

    sessionModalTesting(sessionId) {
        $.post(
            ajax_object.ajax_url, {
                action: 'session_data',
                session_id: sessionId
            },
            (data) => {
                // Insert data into then display modal
                this.initSessionModal({
                    title: data['CustomPresField7'] || 'NNStreamer: Neural Network Stream Pipeline for Efficient and Agile On-Device AI Application Development',
                    location: data['PresentationRoom'] || 'Room 4',
                    description: data['CustomPresField10'] || 'Join Dr. David Rhew, Chief Medical Officer as well as VP and GM of B2B Healthcare at Samsung Electronics',
                    type: data['CustomPresField15'] || 'Panel',
                    level: data['PresentationTargetAudience'] || 'Intermediate',
                    date: data['PresentationDate'],
                    timeStart: data['PresentationDateTimeStart'],
                    timeEnd: data['PresentationDateTimeEnd'],
                    topics: [data['CustomPresField2'], data['CustomPresField3'], data['CustomPresField5']],
                    presenters: data['Presenters']
                })
            },
            'json'
        );
    }

    initSessionModal(data) {
        Object.entries({
            '.modal__session__title': data['title'],
            '.modal__session__location': data['location'],
            '.modal__session__description': data['description'],
            '.modal__session__meta__type': data['type'],
            '.modal__session__meta__level': data['level'],
        }).forEach((field) => {
            $(field[0]).text(field[1])
        });

        let day = moment(data['date']).format('dddd, MMMM D');
        let timeStart = moment(data['timeStart']).format('H:mm')
        let timeEnd = moment(data['timeEnd']).format('H:mm A');
        $('.modal__session__date-time').text(`${day}, ${timeStart} - ${timeEnd}`);

        // const topics = data['topics'];
        const topics = ['Health', 'Stuff'];
        topics.forEach((el) => {
            if (el) {
                $('<li>', {
                    text: el
                }).appendTo('.modal__topics__list')
            }
        });

        const $speakersListEl = $(".modal__session__speakers-list");
        data['presenters'].forEach((presenterObj) => {
            let $speakerEl = $("<div/>", {
                class: 'modal__session__speaker',
                'data-toggle': "modal",
                'data-target': "#speakerModal",
            });

            if (presenterObj.hasOwnProperty('PresenterPhotoFileName')) {
                $("<div/>")
                    .append(
                        $("<img/>", {
                            class: "modal__session__speaker-list__image",
                            src: presenterObj.PresenterPhotoFileName
                        })
                    ).appendTo($speakerEl);
            }

            $("<div/>")
                .append($("<p/>", {
                    class: 'modal__subheading modal__session__speaker__name',
                    text: `${presenterObj.PresenterFirstName} ${presenterObj.PresenterLastName}`
                }))
                .append($("<p/>"), {
                    class: 'modal__session__speaker__title',
                    text: `${presenterObj.pedCustomField2}`
                })
                .appendTo($speakerEl);

            $speakerEl.appendTo($speakersListEl);
        });
        // this.preloadPresenterData( data['Presenters'] );

        $("#sessionModal").modal('show');
    }

    speakerModalTesting(speakerId){
        $.post(
            ajax_object.ajax_url, {
                action: 'speaker_data',
                speaker_id: speakerId
            },
            (data) => {
                console.log( data );
                this.initSpeakerModal({
                    fname: data.PresenterFirstName,
                    lname: data.PresenterLastName,
                    twitter: data.PresenterTwitterHandle,
                    linkedin: data.PresenterLinkedIn,
                    facebook: data.PresenterFacebook,
                    instagram: data.PresenterInstagram,
                    title: data.pedCustomField2,
                    company: data.pedCustomField1,
                    bio: data.pedCustomField4,
                    picture: data.PresenterPhotoFileName
                })
            },
            'json'
        );
    }

    initSpeakerModal(data){
        $('.modal__speaker__image img').attr('src', data.picture);
        $('.modal__speaker__name').text(`${data['fname']} ${data['lname']}`)


        $("#speakerModal").modal('show');
    }
}

jQuery(document).ready(function() {
    new Schedule();
});
