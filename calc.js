$(document).ready(function() {
    $('.keys button').click(function() {
      var buttonText = $(this).text();
      var dsiplay = $('#display');
  
      if (buttonText === '=') {
        try {
          var ergebnis = eval(dsiplay.val());
          dsiplay.val(ergebnis);
        } catch (error) {
          dsiplay.val('Error');
        }
      } else {
        dsiplay.val(dsiplay.val() + buttonText);
      }
    });
  });