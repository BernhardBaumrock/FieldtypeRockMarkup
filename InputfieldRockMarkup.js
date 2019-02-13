console.log('loaded');

$(document).on('RockMarkup.init', function(e) {
  console.log('triggered', $(e.target));
});
