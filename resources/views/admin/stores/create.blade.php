@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Add New Store</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.stores.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.stores.form')
                <button type="submit" class="btn btn-primary">Create Store</button>
            </form>
        </div>
    </div>
</div>
@endsection