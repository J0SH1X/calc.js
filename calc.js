$(document).ready(function() {
  function generateId() {
      return 'id-' + Math.random().toString(36).substr(2, 16);
  }

  var browser_id = localStorage.getItem('browser_id') || (localStorage.setItem('browser_id', generateId()), localStorage.getItem('browser_id'));

  $('.keys button').click(function() {
      var btn = $(this).text()
      var display = $('#display');
      if (!display.length) return;

      if (btn === '=') {
          try {
              var exp = display.val().trim();
              if (!/^[\d+\-*/(). ]+$/.test(exp)) return display.val('Error'); //ensure no sql injection can be performned by sanitazing the input
              var res = eval(exp);
              display.val(res);
              $.post('backend.php', { browser_id, expression: exp, result: res });
          } catch {
              display.val('Error');
          }
      } else {
          display.val(display.val() + btn);
      }
  });
});
