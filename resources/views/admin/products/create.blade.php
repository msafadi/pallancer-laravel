@extends('layouts.admin')

@section('content')
<h1>Create Product</h1>

<form action="{{ route('admin.products.store') }}" method="post" enctype="multipart/form-data">
    @include('admin.products._form', [
        'product' => new App\Product(),
        'gallery' => [],
    ])
</form>
@endsection

