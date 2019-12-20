jQuery(window).load(function() {
  jQuery('.history-slider').flexslider({
    animation: "slide",
    manualControls: ".flex-control-nav li",
      useCSS: false, /* Chrome fix*/
    prevText:"",
    nextText: ""
  });
  jQuery('.btn-view-next-year').click(function (e) {
    jQuery(".flex-next").click();
  });
});
