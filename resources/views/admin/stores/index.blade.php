@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manage Stores</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Store Locator Page Hero Image</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.stores.updateHero') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="store_locator_hero_image">Upload New Hero Image</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="store_locator_hero_image" name="store_locator_hero_image" required>
                                <label class="custom-file-label" for="store_locator_hero_image">Choose file...</label>
                            </div>
                            @error('store_locator_hero_image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    @if($heroImage)
                        <div class="col-md-4 text-center text-md-right">
                            <label>Current Image:</label><br>
                            <img src="{{ asset('uploads/website/' . $heroImage) }}" alt="Current Hero Image" style="max-height: 80px; width: auto; border-radius: 5px;">
                        </div>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary mt-3">Upload Image</button>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Store Locations</h6>
            <a href="{{ route('admin.stores.create') }}" class="btn btn-success btn-sm">Add New Store</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Division</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stores as $store)
                            <tr>
                                <td>{{ $store->name }}</td>
                                <td>{{ $store->division }}</td>
                                <td>{{ $store->address }}</td>
                                <td>{{ $store->phone }}</td>
                                <td>
                                    <a href="{{ route('admin.stores.edit', $store) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('admin.stores.destroy', $store) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this store?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No stores found. Please add a new store.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection