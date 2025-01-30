$(document).ready(function() {
  function generateId() {
      return 'id-' + Math.random().toString(36).substr(2, 16);
  }

  var browser_id = localStorage.getItem('browser_id') || (localStorage.setItem('browser_id', generateId()), localStorage.getItem('browser_id'));
  let historyIndex = -1;
  let historyLength = 0;
  let lastExpression = "";

  function updateHistoryLength() {
      $.post('backend.php', { browser_id, action: 'getHistoryLength' }, function(response) {
          if (response) {
              try {
                  historyLength = parseInt(response);
              } catch (error) {
                  console.error("Error getting history length:", error);
              }
          }
      });
  }

  function loadHistory(index) {
      $.post('backend.php', { browser_id, action: 'loadHistory', offset: index }, function(response) {
          if (response) {
              try {
                  const history = JSON.parse(response);
                  if (history && history.expression) {
                      $('#display').val(history.expression);
                      historyIndex = index;
                      if (index === 0) {
                          lastExpression = history.expression;
                      }
                  } else {
                      $('#display').val("");
                  }
              } catch (error) {
                  console.error("Error parsing history:", error);
                  $('#display').val("Error loading history");
              }
          }
      });
  }

  updateHistoryLength();
  $('#display').val("");

  $('.keys button').click(function() {
      var btn = $(this).text();
      var display = $('#display');
      if (!display.length) return;

      if (btn === '=') {
          try {
              var exp = display.val().trim();
              if (!/^[\d+\-*/(). ]+$/.test(exp)) return display.val('Error');
              
              if (exp === lastExpression) {
                  var res = eval(exp);
                  display.val(res);
                  return;
              }

              var res = eval(exp);
              display.val(res);
              lastExpression = exp;

              $.post('backend.php', { browser_id, expression: exp, result: res, action: 'save' }, function() {
                  updateHistoryLength();
                  historyIndex = -1;
              });
          } catch {
              display.val('Error');
          }
      } else if (btn === 'C') {
          display.val('');
      } else if (btn === '←') {
          var currentText = display.val();
          display.val(currentText.slice(0, -1));
      } else {
          display.val(display.val() + btn);
      }
  });

  $('#history-up').click(function() {
    if (historyIndex + 1 < historyLength) {
        loadHistory(historyIndex + 1);
        historyIndex++;
    }
  });

  $('#history-down').click(function() {
    if (historyIndex > -1) {
        loadHistory(historyIndex - 1);
        historyIndex--;
    } else {
        $('#display').val("");
    }
  });

  $(document).keydown(function(e) {
      var display = $('#display');
      if (!display.length) return;

      if (e.key === 'ArrowUp') {
          if (historyIndex + 1 < historyLength) {
              loadHistory(historyIndex + 1);
              historyIndex++;
          }
      }

      if (e.key === 'ArrowDown') {
          if (historyIndex > -1) {
              loadHistory(historyIndex - 1);
              historyIndex--;
          } else {
              display.val("");
          }
      }

      if (e.key === 'Backspace') {
          var currentText = display.val();
          display.val(currentText.slice(0, -1));
      }

      if (e.key === 'Escape') {
          display.val('');
      }
  });
});
