@extends('layouts.admin')

@section('title', 'Categories')

@section('sidebar')
@parent
<ul>
  <li>Item 1</li>
  <li>Item 1</li>
  <li>Item 1</li>
</ul>
@endsection

@section('content')

@include('admin._alert')

<div class="d-flex">
  <h1 class="h3 mb-4 text-gray-800">Categories</h1>
  <div class="ml-auto">
    <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-outline-success">Create new</a>
  </div>
  
</div>

<table class="table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Parent</th>
      <th>Status</th>
      <th>Products #</th>
      <th>Created At</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @foreach ($entries as $category)
    <tr>
      <td>{{ $category->id }}</td>
      <td>{{ $category->name }}</td>
      <td>{{ $category->parent->name }}</td>
      <td>{{ $category->status }}</td>
      <td>{{ $category->products_count }}</td>
      <td>{{ $category->created_at }}</td>
      <td>
        <div class="d-flex">
          <a class="btn btn-outline-primary btn-sm mr-1" href="{{ route('admin.categories.edit', [$category->id]) }}">Edit</a>
          <form method="post" action="{{ route('admin.categories.delete', [$category->id]) }}">
            @method('delete')
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm delete">Delete</button>
          </form>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

{{ $entries->links() }}

@endsection