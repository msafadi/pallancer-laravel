<!-- CSRF Token -->
@csrf
<div class="form-group">
    <label for="name">Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $product->name) }}">
    @error('name')
    <p class="text-danger">{{ $message }}</p>
    @enderror
</div>
<div class="form-group">
    <label for="category_id">Category</label>
    <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
        <option value="">Select Category</option>
        @foreach (App\Category::all() as $category)

        <option @if($category->id == old('category_id', $product->category_id)) selected @endif value="{{ $category->id }}">{{ $category->name }}</option>

        @endforeach
    </select>
    @error('category_id')
    <p class="text-danger">{{ $message }}</p>
    @enderror
</div>
<div class="form-group">
    <label for="price">Price</label>
    <input type="text" class="form-control @error('price') is-invalid @enderror" name="price" id="price" value="{{ old('price', $product->price) }}">
    @error('price')
    <p class="text-danger">{{ $message }}</p>
    @enderror
</div>
<div class="form-group">
    <label for="image">Image</label>
    <div class="d-flex">
        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image">
        @if ($product->image)
        <img src="{{ asset('images/' . $product->image) }}" height="150" class="ml-auto">
        @endif
    </div>
    @error('image')
    <p class="text-danger">{{ $message }}</p>
    @enderror
</div>
<button type="submit" class="btn btn-primary">Save</button>