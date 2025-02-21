<!-- 
  Content on this page will be displayed on your extension's admin page.
-->
<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">Information</h3>
  </div>
  <div class="box-body">
    <!-- Display success message if available -->
    @if(isset($message) && $message)
      <div class="alert alert-success">
        {{ $message }}
      </div>
    @endif

    <p>
      An Extension Used To <code>Clear/Purge</code> old Activity Logs, in order to clear database storage used.
    </p>

    <!-- The form posts to the same page -->
    <form method="POST" action="{{ $root }}">
      @csrf
      <div class="form-group">
        <label for="timestamp">Purge logs older than:</label>
        <input type="datetime-local" name="timestamp" id="timestamp" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-danger">Purge Logs</button>
    </form>
  </div>
</div>
