/*
--------------------------------------------------------------
  Template Name: Soyuz - Bootstrap 4x Admin Dashboard Template
  File: Core JS File
--------------------------------------------------------------
 */
"use strict";
$(function() {
    
    $.sidebarMenu($('.vertical-menu'));
    $(function() {
        for (var a = window.location, abc = $(".vertical-menu a").filter(function() {
            return this.href == a;
        }).addClass("active").parent().addClass("active"); ;) {
            if (!abc.is("li")) break;            
            $(".vertical-menu-detail .tab-pane").removeClass("show active");
            abc = abc.parent().addClass("in").parent().addClass("show active");
            if ($(".vertical-menu-detail #v-pills-crm").hasClass("show active")) {
                $(".vertical-menu-icon .nav-link").removeClass("active");
                $(".vertical-menu-icon #v-pills-crm-tab").addClass("active");
            }
            if ($(".vertical-menu-detail #v-pills-ecommerce").hasClass("show active")) {
                $(".vertical-menu-icon .nav-link").removeClass("active");
                $(".vertical-menu-icon #v-pills-ecommerce-tab").addClass("active");
            }
            if ($(".vertical-menu-detail #v-pills-hospital").hasClass("show active")) {
                $(".vertical-menu-icon .nav-link").removeClass("active");
                $(".vertical-menu-icon #v-pills-hospital-tab").addClass("active");
            }
            if ($(".vertical-menu-detail #v-pills-uikits").hasClass("show active")) {
                $(".vertical-menu-icon .nav-link").removeClass("active");
                $(".vertical-menu-icon #v-pills-uikits-tab").addClass("active");
            }
            if ($(".vertical-menu-detail #v-pills-pages").hasClass("show active")) {
                $(".vertical-menu-icon .nav-link").removeClass("active");
                $(".vertical-menu-icon #v-pills-pages-tab").addClass("active");
            }
        }
    });
    

   
    $("#infobar-settings-open").on("click", function(e) {
        e.preventDefault();
        $(".infobar-settings-sidebar-overlay").css({"background": "rgba(0,0,0,0.4)", "position": "fixed"});
        $("#infobar-settings-sidebar").addClass("sidebarshow");
    }); 
    $("#infobar-settings-close").on("click", function(e) {
        e.preventDefault();
        $(".infobar-settings-sidebar-overlay").css({"background": "transparent", "position": "initial"});
        $("#infobar-settings-sidebar").removeClass("sidebarshow");
    });
    
    $(".menu-hamburger").on("click", function(e) {
        e.preventDefault();
        $("body").toggleClass("toggle-menu");
        $(".menu-hamburger span").toggle();
    });
   
    $(".topbar-toggle-hamburger").on("click", function(e) {
        e.preventDefault();
        $("body").toggleClass("topbar-toggle-menu");
        $(".topbar-toggle-hamburger img").toggle();    
    });
  
    function mediaSize() { 
        if (window.matchMedia('(max-width: 767px)').matches) {
            $("body").removeClass("toggle-menu");
            $(".menu-hamburger img.menu-hamburger-close").hide();
            $(".menu-hamburger img.menu-hamburger-collapse").show();         
        }
    };

    $(".leftbar").hover(function(){
        $(this).toggleClass("active");
    });

    mediaSize();
    window.addEventListener('resize', mediaSize, false);
  
    $('[data-toggle="popover"]').popover();
  
    $('[data-toggle="tooltip"]').tooltip();
});