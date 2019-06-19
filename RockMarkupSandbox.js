/**
 * This JS file is only loaded when the ProcessHello module is run
 *
 * You should delete it if you have no javascript to add.
 *
 */
console.log('RockMarkupSandbox.js');

$(document).ready(function() {
  // submit form on AJAX setting change
  $('#Inputfield_ajax').change(function() {
    $(this).closest('form').submit();
  });

  // log all RockMarkup events to console
  $('#Inputfield_04-events').on('load.RockMarkup', function(event) {
    console.log('fired', event)
  });

  $("body").on('load.RockMarkup', function(event) {
    console.log('fired on body', event)
  });
  
  $(document).on('load.RockMarkup', function(event) {
    console.log('fired on body', event)
  });

  // copy text on click
  $(document).on('click', '.copy', function() {
    // get value
    var val = $(this).closest('a').find('span').text();

    // copy to clipboard
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(val).select();
    document.execCommand("copy");
    $temp.remove();

    // show notification
    UIkit.notification({
      message: 'copied to clipboard',
      timeout: 2000
    });

    return false;
  });
}); 
