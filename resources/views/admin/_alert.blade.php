@if (session()->has('alert.success'))
<div class="alert alert-success">
  {{ session('alert.success') }}
</div>
@endif