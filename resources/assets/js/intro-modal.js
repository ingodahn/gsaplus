function openModal() {
  var src = 'https://www.youtube.com/embed/LS-VPyLaJFM?html5=1&autoplay=1';
  $('#intro-modal').modal('show');
  $('#intro-modal iframe').attr('src', src);
}

$('#intro-modal').on('hidden.bs.modal', function () {
  $('#intro-modal iframe').removeAttr('src');
});
