@extends('layouts.main')
@push('title')
    <title>Single File Upload</title>
@endpush
@section('main-section')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-10 mt-5 ">
                <form class="row g-3" action="{{ Route("formwork") }}" enctype="multipart/form-data" method="POST" role="form">
                    @csrf
                    @if (isset($edit[0]->id)) <input type="hidden" name="id" value="{{ $edit[0]->id }}"> @endif
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control" value="@if (isset($edit[0]->name)){{ $edit[0]->name }}@endif"
                                id="floatingInput" placeholder="name">
                            <label for="floatingInput">Name</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control" value="@if (isset($edit[0]->email)){{ $edit[0]->email }}@endif"
                                id="floatingInput" placeholder="name@example.com">
                            <label for="floatingInput">Email address</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select form-select-lg" name="privacy" aria-label="Default select example">
                            <option value="public" @if (isset($edit[0]->privacy) && $edit[0]->privacy == 'public')selected @else selected @endif>Public</option>
                            <option value="storage" @if (isset($edit[0]->privacy) && $edit[0]->privacy == 'storage')selected @endif>Storage</option>
                        </select>

                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="filename" class="form-control" value="@if(isset($edit[0]->filename))@php echo explode("_",$edit[0]->filename)[0]@endphp @endif"
                            id="floatingPassword" placeholder="Password">
                            <label for="floatingPassword">File Name</label><br>
                        </div>
                        @if (isset($edit[0]->filename)&&$edit[0]->filename!="") <img width="100" @if($edit[0]->privacy=="public") src="{{ URL::asset('uploads/' . $edit[0]->filename) }}" @else src="{{ URL::asset('storage/uploads/' . $edit[0]->filename) }}"  @endif alt=""/> <br> <a href="{{ Route("remove_img",$edit[0]->id) }}">Remove</a>  @endif
                        <input class="form-control mt-3" value="" name="files" type="file" id="formFileMultiple" >
                    </div>
                    <div class="col-12">
                        @if (isset($edit[0]->id))
                        <a type="submit" href="/" class="btn btn-info">Home</a>
                        @endif
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row justify-content-center">
            {{-- <div class="col-10 mt-5">
                <a href="form" class="btn btn-success">Add Data</a>
            </div> --}}
            <div class="col-12">
                {{-- @if (!isset($edit[0]->id)) --}}
                <div class="table-responsive py-5">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">File Name</th>
                                <th scope="col">Image</th>
                                <th scope="col">Privacy</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- storage_path(); Show Images From Storage path --}}
                            @foreach ($list as $item)
                                <tr>
                                    <th scope="row">{{ $item->id }}</th>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->filename }}</td>
                                    <td><img width="50" @if ($item->privacy == 'public') src="{{ URL::asset('uploads/' . $item->filename) }}" @else src="{{ URL::asset('storage/uploads/' . $item->filename) }}" @endif alt=""></td>
                                    <td>{{ $item->privacy }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                aria-expanded="false">Action </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item" href="{{ Route("edit",$item->id) }}">Edit
                                                    </a>
                                                </li>
                                                <li><a class="dropdown-item"
                                                        onclick=" if(confirm('Are you sure you want to delete this item?')){ return true;}else{return false;}"
                                                        href="{{ Route("delete",$item->id) }}">Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- @endif --}}
            </div>
        </div>
    </div>
@endsection
    