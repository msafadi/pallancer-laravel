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
    <label for="description">{{ __('Description') }}</label>
    <textarea rows="5" class="form-control @error('description') is-invalid @enderror" name="description" id="description">{{ old('description', $product->description) }}</textarea>
    @error('description')
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
<div class="form-group">
    <label for="gallery">Gallery Images</label>
    <input type="file" class="form-control @error('gallery') is-invalid @enderror" name="gallery[]" id="gallery" multiple>
    <div class="d-flex flex-wrap">
        @foreach ($gallery as $image)
        <div class="m-2">
            <img class="rounded border p-1" src="{{ asset('images/' . $image->path) }}" height="90">
        </div>
        @endforeach
    </div>
    @error('gallery')
    <p class="text-danger">{{ $message }}</p>
    @enderror
</div>
<div class="form-group">
    <label for="tags">Tags</label>
    <input type="tags" class="form-control @error('tags') is-invalid @enderror" name="tags" id="tags" value="{{ old('tags', implode(', ', $product->tags->pluck('name')->toArray())) }}">
    @error('tags')
    <p class="text-danger">{{ $message }}</p>
    @enderror
</div>
<button type="submit" class="btn btn-primary">Save</button>

@push('js')
<script src="{{ asset('js/trumbowyg/trumbowyg.min.js') }}"></script>
@endpush
@push('js')
<script>
    $('#description').trumbowyg();
</script>
@endpush

@push('css')
<link rel="stylesheet" href="{{ asset('js/trumbowyg/ui/trumbowyg.min.css') }}">
@endpush