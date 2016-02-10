<div class="container alerts-container">
  @if (isset($errors) && count($errors))
    @foreach($errors->all() as $error)
      <div class="alert alert-dismissible alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        {{ $error }}
      </div>
    @endforeach
  @endif
</div>