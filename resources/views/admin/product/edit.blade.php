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
                        <h4 class="mb-2">{{ __('Update Product') }} #{{ $product->id }}</h4>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list-ul"></i> {{ __('List Product') }}
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Update Product') }}</div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('admin.products.update', $product->id) }}" >
                            @method('PATCH')
                            @csrf
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="name">{{ __('Product Name') }} :</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter Product Name" name="title" value="{{ $product->title }}" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="slug">Slug :</label>
                                    <input type="text" class="form-control" id="slug" placeholder="Enter Slug, ex: the-product-name" name="slug" value="{{ $product->slug }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-12">
                                    <label for="content">{{ __('Description') }} :</label>
                                    <textarea class="form-control" id="content" name="description" placeholder="Enter Description">{{ $product->description }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="content">{{ __('Category') }} :</label>
                                    <select name="category_id" class="form-control">
                                    @foreach ($category_list as $category_id => $category_name)
                                        <option value="{{ $category_id }}" @if ($category_id == $product->category_id) selected="selected" @endif>{{ $category_name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="content">Status :</label>
                                    <select name="active" class="form-control">
                                    @foreach ($status_list as $status_id => $status_name)
                                        <option value="{{ $status_id }}" @if ($status_id == $product->active) selected="selected" @endif>{{ $status_name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>

                            {{--add more --}}
                            <div class="hapus row mb-3 after-add-more">
                                <div class="form-group col-sm-3">
                                    <label for="price">{{ __('Price') }} :</label>
                                        <input type="text" class="form-control" id="price" placeholder="Enter product price" name="price" @if (count($unit_price) > 0) value="{{ $unit_price[0]->price}}" @endif required>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="Unit">{{ __('Unit') }} :</label>
{{--                                    @php--}}
{{--                                        $units = get_product_units();--}}
{{--                                    @endphp--}}
                                    <select class="form-control" name="unit_id">
                                        @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}" @if ($unit->id == $product->unit_id) selected="selected" @endif>{{ $unit->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{--more--}}
                            <div class="row mb-3">
                                <div class="form-group col-sm-3">
                                    <input type="text" class="form-control" id="price" placeholder="Enter product price" name="price2" @if (count($unit_price) > 0) value="{{ $unit_price[1]->price}}" @endif required>
                                </div>
                                <div class="form-group col-sm-3">
                                    <select class="form-control" name="unit_id2">
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}" @if ($unit->id == $product->unit_id2) selected="selected" @endif>{{ $unit->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="form-group col-sm-3">
                                    <input type="text" class="form-control" id="price" placeholder="Enter product price" name="price3" @if (count($unit_price) > 0) value="{{ $unit_price[2]->price}}" @endif required>
                                </div>
                                <div class="form-group col-sm-3">
                                    <select class="form-control" name="unit_id3">
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}" @if ($unit->id == $product->unit_id3) selected="selected" @endif>{{ $unit->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="form-group col-sm-4">
                                    <label for="discount">{{ __('Discount') }} :</label>
                                    <input type="text" class="form-control" id="discount" placeholder="Enter Product discount" name="discount" value="{{ $product->discount }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="stock">{{ __('Stock') }} :</label>
                                    <input type="text" class="form-control" id="stock" placeholder="Enter product stock" name="stock" value="{{ $product->stock }}" required>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="weight">{{ __('Weight') }} :</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="weight" placeholder="Enter Product weight" name="weight" value="{{ $product->weight }}">

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <div class="checkbox-theme-default custom-checkbox ">
                                           <input class="checkbox" type="checkbox" id="check-un3" name="enabled" @if ($product->enabled > 0) checked @endif>
                                           <label for="check-un3">
                                           <span class="checkbox-text">
                                            {{ __('Enable') }}
                                           </span>
                                           </label>
                                        </div>
                                     </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <div class="checkbox-theme-default custom-checkbox ">
                                           <input class="checkbox" type="checkbox" id="check-un4" name="hidden" @if ($product->hidden > 0) checked @endif>
                                           <label for="check-un4">
                                           <span class="checkbox-text">
                                            {{ __('Hidden') }}
                                           </span>
                                           </label>
                                        </div>
                                     </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <div class="checkbox-theme-default custom-checkbox ">
                                           <input class="checkbox" type="checkbox" id="check-un5" name="priority" @if ($product->priority == 2) checked @endif>
                                           <label for="check-un5">
                                           <span class="checkbox-text">
                                            {{ __('Favorite Product') }}
                                           </span>
                                           </label>
                                        </div>
                                     </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <input type="hidden" name="id_product" value="{{ $product->id }}"/>
                                <div class="form-group col-sm-12">
                                    <button type="submit" class="btn btn-lg btn-default shadow-sm">{{ __('Update Now') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header color-dark fw-500">{{ __('Product Images') }}</div>
                    <div class="card-body p-2">
                        <div class="row">
                            <div class="col-12 col-lg-6 col-md-6 col-sm-6">
                                @if ($product->images)
                                @php
                                    $images = $product->images()->paginate(10);
                                @endphp
                                <div class="userDatatable global-shadow border-0 bg-white w-100">
                                    <div class="table-responsive">
                                        <table class="table mb-2 table-striped">
                                            <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>{{ __('File Name') }}</th>
                                                <th>Default</th>
                                                <th class="text-center" width="100px">{{ __('Action') }}</th>
                                            </tr>
                                            </thead>
                                            @php
                                                $i = 1;
                                            @endphp
                                            <tbody>
                                            @foreach ($images as $image)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $i + (($images->currentPage()-1) * $images->perPage()) }}
                                                    </td>
                                                    <td>
                                                        {{ $image->getThumbnail() }}
                                                        {{ $image->file_name }}
                                                    </td>
                                                    <td>
                                                        {{ $image->is_default > 0 ? 'Yes' : 'No' }}
                                                    </td>
                                                    <td class="text-center">
                                                        <ul class="list-unstyled list-inline">
                                                            <li class="list-inline-item">
                                                                <form action="{{ route('admin.products.image.destroy', $image->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a role="button" href="javascript:void(0);" onclick="return removeItem(this);" title="remove">
                                                                        <i class="bi bi-trash size-22"></i>
                                                                    </a>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                                @php ++$i @endphp
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @else
                                <div class="alert alert-warning">
                                    {{ __('No image found on this product') }}
                                </div>
                                @endif
                            </div>
                            <div class="col-12 col-lg-6 col-md-6 col-sm-6">
                                <form method="POST" action="{{ route('admin.products.image.create', $product->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mt-3 mb-3">
                                        <div class="form-group col-sm-12">
                                            <label for="name">{{ __('File Name') }} :</label>
                                            <input type="file" class="form-control mt-2" id="name" name="file_name" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <div class="checkbox-theme-default custom-checkbox ">
                                                   <input class="checkbox" type="checkbox" id="check-un4" name="is_default" @if (old('is_default') > 0) checked @endif>
                                                   <label for="check-un4">
                                                   <span class="checkbox-text">Default</span>
                                                   </label>
                                                </div>
                                             </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="form-group col-sm-12">
                                            <button type="submit" class="btn btn-lg btn-default shadow-sm">{{ __('Upload Now') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
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
    function removeItem(dt)
    {
        if (confirm("{{ __('Are you sure you want to delete this?') }}")) {
            $(dt).parent().submit();
        }
        return false;
    }
</script>

{{--script js show hide--}}
<script type="text/javascript">
    $(document).ready(function() {
        $(".add-more").click(function(){
            var html = $(".copy").html();
            $(".after-add-more").after(html);
        });

        // saat tombol remove dklik control group akan dihapus
        $("body").on("click",".remove",function(){
            $(this).parents(".hapus").remove();
        });
    });
</script>
<script>
    function getData() {
        console.log("Hej");
        $.getJSON('http://localhost:53209/api/items', function (data) {
            var html = '';
            var len = data.length;
            for (var i = 0; i < len; i++) {
                html += '<option = value"' + data[i].Name + '">' + data[i].Name + '</option>';
            }
            // console.log(html);
            $('itemSelect').append(html);
        });
    }
</script>

@endsection
