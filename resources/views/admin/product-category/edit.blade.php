@extends('layouts.fe')

@section('content')
<!-- Begin page -->
<main class="h-100 has-header has-footer">

    @include('layouts.partial._header')

    <div class="main-container container">

        @include('layouts.partial._message')

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-center mb-3">
                    <div class="d-flex align-items-center">
                        <h4 class="mb-2">{{ __('Update Category') }} #{{ $productCategory->id }}</h4>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <a href="{{ route('admin.product-categories.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list-ul"></i> {{ __('List Category') }}
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Update Category') }}</div>
                    <div class="card-body p-2">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('admin.product-categories.update', $productCategory->id) }}" enctype="multipart/form-data">
                            @method('PATCH')
                            @csrf
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="name">{{ __('Title') }} :</label>
                                    <input type="text" class="form-control" id="name" placeholder="Masukkan nama kategori" name="title" value="{{ $productCategory->title }}" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="slug">Slug :</label>
                                    <input type="text" class="form-control" id="slug" placeholder="Masukkan Slug, ex: the-product-name" name="slug" value="{{ $productCategory->slug }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="content">{{ __('Description') }} :</label>
                                    <textarea class="form-control" id="content" name="description" placeholder="Masukkan deskripsi">{{ $productCategory->description }}</textarea>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="image">{{ __('Image') }} :</label>
                                    @if (is_array($productCategory->meta))
                                    <div class="row">
                                        <div class="col-sm-6">
                                            {{ $productCategory->getImage(100, 100, ['class' => 'img-fluid'], true) }}
                                        </div>
                                    </div>
                                    @endif
                                    <input type="file" class="form-control mt-2" id="image" name="file_name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <button type="submit" class="btn btn-lg btn-default shadow-sm">{{ __('Update Now') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Page ends-->
@endsection

@section('js')
<script type="text/javascript">
    function setTheSlug(dt){
        var str = $(dt).val();
        if (str.length > 0) {
            var slug = str.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
            $('#slug').val(slug);
        } else {
            $('#slug').val('');
        }
    }
</script>
@endsection