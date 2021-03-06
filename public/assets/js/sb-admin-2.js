(function($) {
  "use strict"; // Start of use strict 

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(function() {
    if ($(window).width() < 768) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
    if ($(window).width() > 768) {
      var e0 = e.originalEvent,
        delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });

  // Scroll to top button appear
  $(document).on('scroll', function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function(e) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
  });
  //------------------------
  //---------validaciones---------------
  //------------------------
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
    
    $(function(){ const newLocal = $('.toggle-event').bootstrapToggle(); }); //boton toggle
    //--------TOOLTIP
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })
    //------SELECT2
    $(document).ready(function() {
      // ejemplo de popover
      
      $('[data-toggle="popover"]').popover({
        trigger: 'hover',
        placement: 'auto'
      })
      // Toggle the side navigation
      //---------------------------
      $('.js-example-basic-single').select2({
        theme: "bootstrap",
        language: "es"
      });
      $("#sidebarToggle, #sidebarToggleTop").on('click', function (e) {
        $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");
        if ($(".sidebar").hasClass("toggled")) {
          $('.sidebar .collapse').collapse('hide');
        };
      });
      $('#ShowPassword').click(function () {
        $('#Password').attr('type', $(this).is(':checked') ? 'text' : 'password');
      });
      //getCamas(1);
      camasPorServicio();
      camasEstado();
      mapaDeCamas(0);
      ultima_conexion();
      $('#calendario').popover({
        trigger: 'hover',
        placement: 'auto',
        title: 'Fecha',
        content: moment().format("dddd, MMMM Do YYYY, h:mm a")
      });
      // sala de espera
      
      
      dataTables();
      //-------------------------
      $('.btn-icon-split').addClass('lift');
      
      
      
    
  });
  
})(jQuery); // End of use strict
