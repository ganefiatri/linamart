<!-- filter menu -->
<div class="filter">
    <div class="card shadow h-100">
        <div class="card-header">
            <div class="row">
                <div class="col align-self-center">
                    <h6 class="mb-0">{{ __('Filter Criteria') }}</h6>
                    <p class="text-opac">{{ $totActiveProduct }} products</p>
                </div>
                <div class="col-auto px-0">
                    <button class="btn btn-link text-danger filter-close">
                        <i class="bi bi-x size-22"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body overflow-auto">
            <form method="GET" action="{{ route('member.order.search') }}">
            <div class="mb-4">
                <h6>{{ __('Select Range') }}</h6>
                <div id="rangeslider" class="mt-4"></div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <div class="form-floating">
                        <input type="number" class="form-control" min="10000" max="1000000" name="price_from" value="{{ request('price_from') }}" placeholder="10.000" step="1" id="input-select">
                        <label for="input-select">{{ __('Min') }}</label>
                    </div>
                </div>
                <div class="col-auto align-self-center"> {{ __('to') }} </div>
                <div class="col">
                    <div class="form-floating">
                        <input type="number" class="form-control" min="10000" max="1000000" name="price_to" value="{{ request('price_to') }}" placeholder="100.000" step="1" id="input-number">
                        <label for="input-number">{{ __('Max') }}</label>
                    </div>
                </div>
            </div>

            <div class="form-floating mb-4">
                <select class="form-control" id="filtertype" name="category">
                    <option value="">-</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" 
                        @if (request('category') == $category->id) selected @endif>
                        {{ $category->title }}</option>
                    @endforeach
                </select>
                <label for="filtertype">{{ __('Select Category') }}</label>
            </div>

            {{--
            <div class="form-group floating-form-group active mb-4">
                <h6 class="mb-3">Select Facilities</h6>

                <div class="form-check form-switch mb-2">
                    <input type="checkbox" class="form-check-input" id="men" checked>
                    <label class="form-check-label" for="men">Men</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input type="checkbox" class="form-check-input" id="woman" checked>
                    <label class="form-check-label" for="woman">Women</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input type="checkbox" class="form-check-input" id="Sport">
                    <label class="form-check-label" for="Sport">Sport</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input type="checkbox" class="form-check-input" id="homedecor">
                    <label class="form-check-label" for="homedecor">Home Decor</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input type="checkbox" class="form-check-input" id="kidsplay">
                    <label class="form-check-label" for="kidsplay">Kid's Play</label>
                </div>
            </div>
            --}}

            <div class="form-floating mb-3">
                <input type="text" name="q" class="form-control" placeholder="Keyword" value="{{ request('q') }}">
                <label for="input-select">Keyword</label>
            </div>

            {{--
            <div class="form-floating mb-3">
                <select class="form-control" id="discount">
                    <option>10% </option>
                    <option>30%</option>
                    <option>50%</option>
                    <option>80%</option>
                </select>
                <label for="discount">Offer Discount</label>
            </div>
            --}}
        </div>
        <div class="card-footer">
            <button class="btn btn-lg btn-default w-100 shadow-sm" type="submit">{{ __('Search') }}</button>
        </div>
        </form>
    </div>
</div>
<!-- filter menu ends-->