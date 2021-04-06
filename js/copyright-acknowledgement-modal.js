(function ($) {
  Drupal.behaviors.copyright_acknowledgement_link = {
    attach: function (context, settings) {
      // Enable the modal (which will also hide it).
      $('#copyright-acknowledgement').addClass("modal");

      // Show the close button (hidden by default for those without JS).
      $('.close').css("display","block");

      // Turn the download text into a modal button.
      $('#download-modal-link').click(function () {
        $('#copyright-acknowledgement').css("display","block");
      });
      $('#download-modal-link').addClass("modal-link-button");

      // Closing the modal, button and outside modal
      $("#copyright-acknowledgement-close-button").click(function () {
        $('#copyright-acknowledgement').css("display","none");
      });
      $(window).click(function (event) {
        if (event.target.id == 'copyright-acknowledgement') {
          $('#copyright-acknowledgement').css("display","none");
        }
      });
    }
  }
})(jQuery);
