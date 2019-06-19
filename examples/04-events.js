console.log('04-events.js');

$(document).ready(function() {

  var logEvent = function(event, num) {
    console.log('Event was fired:', event);
  }

  $(document).on('RockMarkup', function(event) { logEvent(event); });
  $(document).on('loaded', function(event) { logEvent(event); });
  $(document).on('size', function(event) { logEvent(event); });
});