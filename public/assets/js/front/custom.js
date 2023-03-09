(function($) {
    
    "use strict";
    var music = {
        initialised: false,
        version: 1.0,
        mobile: false,
        init: function() {
            if (!this.initialised) {
                this.initialised = true;
            } else {
                return;
            }
            /*-------------- Music Functions Calling ---------------------------------------------------
            ------------------------------------------------------------------------------------------------*/
            this.RTL();
            this.profile_toggle();
            this.dropdown_toggle();
            this.Trending_Slider();
            this.Recommended_Slider();
            this.Topalbums_Slider();
            this.Reco_albums_Slider();
            this.music_center_Slider();
            this.alsolike_Slider();
            this.alsolike_Slider2();
            this.Menu();
            this.Player_close();
            this.More();
            this.showPlayList();
            this.volume();
            this.selctType();
            this.playList();
            this.Popup();
        },
        /*-------------- Music Functions definition ---------------------------------------------------
        ---------------------------------------------------------------------------------------------------*            

        /*-----------------------------------------------------
		RTL
		-----------------------------------------------------*/
        RTL: function() {
            var rtl_attr = $("html").attr('dir');
            if (rtl_attr) {
                $('html').find('body').addClass("rtl");
            }
        },
        /*-----------------------------------------------------
		Profile Toggle
		-----------------------------------------------------*/
        profile_toggle: function() {
            if ($('.ms_pro_inner').length > 0) {
                $(document).on('click', '.ms_pro_inner ', function(event) {
                    event.stopPropagation();
                    $(this).toggleClass('show');
                })

                // outside remove click
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.ms_pro_inner').length) {
                        $('.ms_pro_inner').removeClass('show');
                    }
                });

                $(document).on('click', 'body ', function() {
                    $(".ms_pro_inner").removeClass('show')
                })
            }
        },
        /*-----------------------------------------------------
		songs list Dropdown Toggle
		-----------------------------------------------------*/
        dropdown_toggle: function() {
            if ($('.songslist_moreicon').length > 0) {
                $('.songslist_moreicon').on('click', function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    $('.songslist_moreicon').not($(this)).closest('li').find('.ms_common_dropdown').removeClass('open');
                    $(this).closest('li').find('.ms_common_dropdown').toggleClass('open');
                });
                $(document).on('click', '.songslist_moreicon', function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    $('.songslist_moreicon').not($(this)).closest('li').find('.ms_common_dropdown').removeClass('open');
                    $(this).closest('li').find('.ms_common_dropdown').toggleClass('open');
                });
                $(document).on('click', 'body', function(event) {
                    event.stopPropagation();
                    $('.ms_common_dropdown').removeClass('open');
                });
            }else{
                $(document).on('click', '.songslist_moreicon', function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    $('.songslist_moreicon').not($(this)).closest('li').find('.ms_common_dropdown').removeClass('open');
                    $(this).closest('li').find('.ms_common_dropdown').toggleClass('open');
                });
            }
        },
        // Trending Artist  Slider
        Trending_Slider: function() {
            var swiper = new Swiper('.trending_artist_slider .swiper-container', {
                slidesPerView: 7,
                spaceBetween: 26,
                loop: false,
                speed: 1500,
                navigation: {
                    nextEl: '.swiper-button-next1',
                    prevEl: '.swiper-button-prev1',
                },
                breakpoints: {
                    1800: {
                        slidesPerView: 6,
                    },
                    1600: {
                        slidesPerView: 5,
                    },
                    1400: {
                        slidesPerView: 4,
                    },
                    1200: {
                        slidesPerView: 3,
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                    },
                    600: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    420: {
                        slidesPerView: 2,
                        spaceBetween: 0,
                    }
                },
            });
        },
        // Recommended  Artist  Slider
        Recommended_Slider: function() {
            var swiper = new Swiper('.recommended_artist_slider .swiper-container', {
                slidesPerView: 7,
                spaceBetween: 26,
                loop: false,
                speed: 1500,
                navigation: {
                    nextEl: '.swiper-button-next2',
                    prevEl: '.swiper-button-prev2',
                },
                breakpoints: {
                    1800: {
                        slidesPerView: 6,
                    },
                    1600: {
                        slidesPerView: 5,
                    },
                    1400: {
                        slidesPerView: 4,
                    },
                    1200: {
                        slidesPerView: 3,
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                    },
                    600: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    420: {
                        slidesPerView: 2,
                        spaceBetween: 0,
                    }
                },
            });
        },
        // Trending Album  Slider
        Topalbums_Slider: function() {
            var swiper = new Swiper('.top_album_slider .swiper-container', {
                slidesPerView: 6,
                spaceBetween: 26,
                loop: false,
                speed: 1500,
                navigation: {
                    nextEl: '.swiper-button-next1',
                    prevEl: '.swiper-button-prev1',
                },
                breakpoints: {
                    1800: {
                        slidesPerView: 5,
                    },
                    1600: {
                        slidesPerView: 5,
                    },
                    1400: {
                        slidesPerView: 4,
                    },
                    1200: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 10,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    640: {
                        slidesPerView: 2,
                    },
                    420: {
                        slidesPerView: 2,
                    },
                },
            });
        },
        // Recommended  Album  Slider
        Reco_albums_Slider: function() {
            var swiper = new Swiper('.recommended_album_slider .swiper-container', {
                slidesPerView: 6,
                spaceBetween: 26,
                loop: false,
                speed: 1500,
                navigation: {
                    nextEl: '.swiper-button-next2',
                    prevEl: '.swiper-button-prev2',
                },
                breakpoints: {
                    1800: {
                        slidesPerView: 5,
                    },
                    1600: {
                        slidesPerView: 5,
                    },
                    1400: {
                        slidesPerView: 4,
                    },
                    1200: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 10,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    640: {
                        slidesPerView: 2,
                    },
                    420: {
                        slidesPerView: 2,
                    },
                },
            });
        },

        // Playlist Slider 
        playList: function() {
            var swiper = new Swiper('.play-list-slider .swiper-container', {
                slidesPerView: 6,
                spaceBetween: 20,
                loop: false,
                speed: 1500,
                navigation: {
                    nextEl: '.play-list-slider .swiper-button-next1',
                    prevEl: '.play-list-slider .swiper-button-prev1',
                },
                breakpoints: {
                    1800: {
                        slidesPerView: 5,
                    },
                    1600: {
                        slidesPerView: 5,
                    },
                    1400: {
                        slidesPerView: 4,
                    },
                    1200: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    992: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 10,
                    },
                    640: {
                        slidesPerView: 1,
                    },
                    420: {
                        slidesPerView: 2,
                    },
                },
            });
        },


        // You may also like  Slider
        alsolike_Slider: function() {
            var swiper = new Swiper('.also_like_slider .swiper-container', {
                slidesPerView: 4,
                spaceBetween: 30,
                loop: false,
                speed: 1500,
                navigation: {
                    nextEl: '.swiper-button-next1',
                    prevEl: '.swiper-button-prev1',
                },
                breakpoints: {
                    1800: {
                        slidesPerView: 3,
                    },
                    1600: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                    },
                    1500: {
                        slidesPerView: 2,
                    },
                    1399: {
                        slidesPerView: 4,
                        spaceBetween: 10,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 10,
                    },
                    800: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    700: {
                        slidesPerView: 2,
                        spaceBetween: 15,
                    },
                    480: {
                        slidesPerView: 2,
                    }
                },
            });
        },
        // You may also like  Slider
        alsolike_Slider2: function() {
            var swiper = new Swiper('.also_like_slider2 .swiper-container', {
                slidesPerView: 4,
                spaceBetween: 30,
                loop: false,
                speed: 1500,
                navigation: {
                    nextEl: '.swiper-button-next2',
                    prevEl: '.swiper-button-prev2',
                },
                breakpoints: {
                    1800: {
                        slidesPerView: 3,
                    },
                    1600: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                    },
                    1500: {
                        slidesPerView: 2,
                    },
                    1399: {
                        slidesPerView: 4,
                        spaceBetween: 10,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 10,
                    },
                    800: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    700: {
                        slidesPerView: 2,
                        spaceBetween: 15,
                    },
                    480: {
                        slidesPerView: 2,
                    }
                },
            });
        },

        // Music Center  Slider
        music_center_Slider: function() {
            var swiper = new Swiper('.music_center_slider .swiper-container', {
                effect: 'coverflow',
                grabCursor: true,
                centeredSlides: false,
                slidesPerView: 'auto',
                loop: false,
                speed: 600,
                autoplay: false,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                },
                grabCursor: true,
                effect: 'coverflow',
                coverflowEffect: {
                    rotate: 0,
                    stretch: 480,
                    depth: 300,
                },
                navigation: {
                    nextEl: '.swiper-music-next',
                    prevEl: '.swiper-music-prev',
                },
                breakpoints: {

                    575: {
                        coverflowEffect: {
                            stretch: 300,
                        },
                    },
                    600: {
                        coverflowEffect: {
                            stretch: 400,
                        },
                    },
                    700: {
                        coverflowEffect: {
                            stretch: 450,
                        },
                    },
                    800: {
                        coverflowEffect: {
                            stretch: 500,
                        },
                    },
                    992: {
                        coverflowEffect: {
                            stretch: 600,
                        },
                    },
                    1200: {
                        coverflowEffect: {
                            stretch: 500,
                        },
                    },
                    1399: {
                        coverflowEffect: {
                            stretch: 700,
                        },
                    },
                }
            });
        },

        // Toggle Menu
        Menu: function() {
            $(".ms_cmenu_toggle").on('click', function() {
                $("body").toggleClass('open_menu');
            });
            // on click player list
            $(".play-left-arrow").on('click', function() {
                $(".player_left").toggleClass('open_list');
            });
        },
        // Player Close On Click
        Player_close: function() {
            $(".ms_player_close").on('click', function() {
                $(".ms_player_wrapper").toggleClass("close_player");
                $("body").toggleClass("main_class");
                //$('.gotop').toggleClass('extr_top');
            });
            // Video 
            $(".yt_player_close").on('click', function() {
                $(".yt_player_wrapper.yt_player_opened").toggleClass("close_player");
                $("body").toggleClass("main_class");
            })
        },
        Popup: function() {
            $('.clr_modal_btn a').on('click', function(event) {
                event.stopPropagation();
                $('#clear_modal').hide();
                $('.modal-backdrop').hide();
                $('body').removeClass("modal-open").css("padding-right", "0px");
            });

            $('.hideCurrentModel').on('click', function() {
                $(this).closest('.modal-content').find('.form_close').trigger('click');
            });

            $('.lang_list').find("input[type=checkbox]").on('change', function(event) {
                event.stopPropagation();
                if ($('.lang_list').find("input[type=checkbox]:checked").length) {
                    $('.ms_lang_popup .modal-content').addClass('add_lang');
                } else {
                    $('.ms_lang_popup .modal-content').removeClass('add_lang');
                }
            });
        },


        // Queue
        More: function() {
            $(document).on('click', '#playlist-wrap ul li .action .que_more', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                $('#playlist-wrap ul li .action .que_more').not($(this)).closest('li').find('.more_option').removeClass('open_option');
                $(this).closest('li').find('.more_option').toggleClass('open_option');
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('.more_option').length && !$(e.target).closest('.action').length) {
                    $('#playlist-wrap .more_option').removeClass('open_option');
                }
                if (!$(e.target).closest('#playlist-wrap').length && !$(e.target).closest('.jp_queue_wrapper').length && !$(e.target).closest('.player_left').length) {
                    $('#playlist-wrap').slideUp();
                }
            });

        },
        showPlayList: function() {
            $(document).on('click', '#myPlaylistQueue', function(event) {
                event.stopPropagation();
                $('#playlist-wrap').slideToggle();
            });
            $(document).on('click', '.jp_queue_cls', function(event) {
                event.stopPropagation();
                $('#playlist-wrap').slideToggle();
            });
            $('#playlist-wrap').on('click', '#myPlaylistQueue', function(event) {
                event.stopPropagation();
            });
            $(document).on('click', 'body ', function() {
                $("#playlist-wrap").hide();
            })
        },

        // Volume 
        volume: function() {
            $(".knob-mask .knob").css("transform", "rotateZ(270deg)");
            $(".knob-mask .handle").css("transform", "rotateZ(270deg)");
        },

        /*-----------------------------------------------------
		    Selct Type
		-----------------------------------------------------*/
        selctType: function() {
            // if ($('select').length > 0) {
            //     $('select').niceSelect();
            // }

            /**Notification Dropdown */

            $(".noti_icon").on('click', function(e) {
                e.stopPropagation();
                $(".recent-notification").toggleClass('show');
            });
            $('.recent-notification').on('click', function(event) {
                event.stopPropagation();
            });
            /**Notification Option */
            $(".notification-status").on('click', function(e) {
                e.stopPropagation();
                $(this).find(".notification-options").toggleClass('show');
            });
            $('.notification-options').on('click', function(event) {
                event.stopPropagation();
            });


            $('body').on("click", function() {
                $('.notification-options, recent-notification').removeClass('show');
            });
            $('body').on("click", function() {
                $('.recent-notification').removeClass('show');
            });

            $('.close_options').on("click", function(event) {
                event.stopPropagation();
                $('.notification-options').removeClass('show');
            });

        },

    };
    

    $(document).ready(function() {
        music.init();

        // Scrollbar
        $(".ms_nav_wrapper").mCustomScrollbar({
            theme: "minimal"
        });

        // Song list Scrollbar
        $(".ms_songslist_wrap .ms_songslist_box").mCustomScrollbar({
            theme: "minimal",
            setHeight: 610
        });
        
        // music list Scrollbar
        $(".music_listwrap").mCustomScrollbar({
            theme: "minimal",
            setHeight: 350
        });

        // Queue Scrollbar
        $(".jp_queue_list_inner").mCustomScrollbar({
            theme: "minimal",
            setHeight: 345
        });

    });
    // Preloader Js
    jQuery(window).on('load', function() {
        setTimeout(function() {
            $('body').addClass('loaded');
        }, 500);
        // Li Lenght
        if ($('.jp-playlist ul li').length > 3) {
            $('.jp-playlist').addClass('find_li');
        }
    });
    $(window).scroll(function() {
        var scroll = $(window).scrollTop()
        if (scroll >= 5) {
            $(".ms_header").addClass("dark_header");
        } else {
            $(".ms_header").removeClass("dark_header");
        }
    });


})(jQuery);