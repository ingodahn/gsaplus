function startVideo() {
  var src = 'https://www.youtube.com/embed/VPVHZBdQdt8?html5=1&autoplay=1&showinfo=0&rel=0';
  $('#intro-modal iframe').attr('src', src);
}

function stopVideo() {
  $('#intro-modal iframe').attr('src', "about:blank");
}

function openModal() {
  $('#intro-modal').modal('show');
  startVideo();
}

$(function(){
  $('#intro-modal').on('hidden.bs.modal', function () {
    stopVideo();
  });
});

window.onload = function() {
  stopVideo();
};
