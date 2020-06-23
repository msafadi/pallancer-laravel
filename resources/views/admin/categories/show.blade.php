@extends('layouts.admin')

@section('content')

<h1>{{ $category->name }}</h1>

<h2>Sub-Categories</h2>
<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($category->children as $child)
        <tr>
            <td>{{ $child->name }}</td>
            <td>{{ $child->created_at->diffForHumans() }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h2>Category Products</h2>
<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($category->products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


@endsection