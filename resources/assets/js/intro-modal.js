function openModal() {
  var src = 'http://www.youtube.com/v/LS-VPyLaJFM&amp;autoplay=1';
  $('#intro-modal').modal('show');
  $('#intro-modal iframe').attr('src', src);
}

$('#intro-modal').on('hidden.bs.modal', function () {
  $('#intro-modal iframe').removeAttr('src');
});
