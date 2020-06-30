@extends('layouts.admin')

@section('content')
<h1>{{ __('Update Product') }}</h1>

<form action="{{ route('admin.products.update', [$product->id]) }}" method="post" enctype="multipart/form-data">
    @method('put')
    @include('admin.products._form')
</form>

@endsection
