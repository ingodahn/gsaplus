<div class="container alerts-container">
  @foreach (Alert::getMessages() as $type => $messages)
    @foreach ($messages as $message)
      <div class="alert alert-dismissible alert-{{$type}}" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        {{$message}}
      </div>
    @endforeach
  @endforeach
</div>
