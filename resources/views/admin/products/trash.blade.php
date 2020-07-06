@extends('layouts.admin')

@section('title', 'Treashed Products')

@section('content')

@include('admin._alert')

<div class="d-flex">
  <h1 class="h3 mb-4 text-gray-800">@lang('Treashed Products')</h1>
  <div class="ml-auto">
    <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-outline-success">Create new</a>
  </div>
  
</div>

<table class="table">
  <thead>
    <tr>
      <th>Image </th>
      <th>ID</th>
      <th>Name</th>
      <th>Description</th>
      <th>Category</th>
      <th>Price</th>
      <th>Deleted At</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @foreach ($products as $product)
    <tr>
      <td><img height="60" src="{{ asset('images/' . $product->image) }}"></td>
      <td>{{ $product->id }}</td>
      <td>{{ $product->name }}</td>
      <td>{!! $product->description !!}</td>
      <td>{{ $product->category->name }}</td>
      <td>{{ $product->price }}</td>
      <td>{{ $product->deleted_at }}</td>
      <td>
        <div class="d-flex">
          @can('update', $product)
          <form method="post" action="{{ route('admin.products.restore', [$product->id]) }}">
            @method('put')
            @csrf
            <button type="submit" class="btn btn-outline-dark btn-sm">Restore</button>
          </form>
          @endcan
          @can('products.delete', $product)
          <form method="post" action="{{ route('admin.products.forceDelete', [$product->id]) }}">
            @method('delete')
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm delete">Delete</button>
          </form>
          @endcan
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

{{ $products->links() }}

@endsection