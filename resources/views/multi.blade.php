@extends('layouts.main')

@push('title')
    <title>Multiple File Upload</title>
@endpush

@section('main-section')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-10 mt-5 ">
                <form class="row g-3" action="{{ Route('multiinsert') }}" enctype="multipart/form-data"
                    method="POST" role="form">
                    @csrf
                    @if (isset($edit[0]->id)) <input type="hidden" name="id" value="{{ $edit[0]->id }}"> @endif
                    <div class="col-md-12">
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control" value="@if (isset($edit[0]->name)){{ $edit[0]->name }}@endif"
                                id="floatingInput" placeholder="name">
                            <label for="floatingInput">Name</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            @if (isset($edit[0]->filename))
                                @foreach ($edit as $items)
                                    <div style="width:150px;" class="border border-right p-3">
                                        <img style="width:100%;height:70px;object-fit:scale-down;"
                                            src="{{ URL::asset('storage/multi/' . $items['filename']) }}" alt="" />
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col">
                                                    <a class="btn p-1 btn-danger" style="font-size: 10px"
                                                        href="{{ Route('removeone', $items['image_id']) }}">Remove</a>
                                                    <button type="button" onclick="editimg({{ $items['image_id'] }})"
                                                        class="btn p-1 btn-warning" style="font-size: 10px"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#exampleModal">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <input class="form-control" name="files[]" type="file" id="formFileMultiple" accept="image/*"
                            multiple>
                    </div>
                    <div class="col-12">
                        @if (isset($edit[0]->id))
                            <a href="/multi" class="btn btn-info">Go Back</a>
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
                                <th scope="col">File Name</th>
                                <th scope="col">Image</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <th scope="row">{{ $item->id }}</th>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        @php
                                            $i = 1;
                                            foreach ($imgrow as $file) {
                                                if ($file->user_id == $item->id) {
                                                    $file = explode('_', $file->filename)[0];
                                                    echo "<span>$i.  $file </span><br>";
                                                    $i++;
                                                }
                                            }
                                        @endphp
                                    </td>
                                    <td>
                                        @foreach ($imgrow as $file)
                                            @if ($file->user_id == $item->id)
                                                <img width="50" src="{{ URL::asset('storage/multi/' . $file->filename) }}"
                                                    alt="">
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                aria-expanded="false">Action </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item"
                                                        href="{{ Route('multi_edit', $item->id) }}">Edit
                                                    </a>
                                                </li>
                                                <li><a class="dropdown-item"
                                                        onclick=" if(confirm('Are you sure you want to delete this item?')){ return true;}else{return false;}"
                                                        href="{{ Route('multi_del', $item->id) }}">Delete</a></li>
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
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ Route('multiinsert') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="updateid" id="img-id">
                        <input type="file" name="updateimg" accept="image/*">
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editimg(id){
            document.querySelector("#img-id").value=id;
        }
    </script>
@endsection
