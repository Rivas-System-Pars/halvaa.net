<div class="col-lg-3 col-md-12 col-sm-12 sticky-sidebar">
    <div class="dt-sn px-3 mb-3 d-flex align-items-start justify-content-center">
        <form id="products-filter-form " class="w-100 d-flex align-items-center justify-content-center flex-column gap-2" action="{{ route('front.products.category-products', ['category' => $category]) }}">
            <div class="col-12 d-flex align-items-start justify-content-center">
                <div class="section-title text-sm-title title-wide mb-0 no-after-title-wide w-100">
                    <h2>فیلتر محصولات</h2>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="widget-search">
                    <input type="text" name="s" value="{{ request('s') }}"
                        placeholder="نام محصول مورد نظر را بنویسید..." class="w-100">
                    <button class="btn-search-widget">
                        <img src="{{ theme_asset('img/theme/search.png') }}" alt="search buttom">
                    </button>
                </div>
            </div>
            <div class="col-12 filter-product mb-3">
                <div class="accordion d-flex align-items-start justify-content-center flex-column gap-3" id="accordionExample">

                    @php
                        $products_id = $category->allPublishedProducts()->pluck('id');
                    @endphp

                    @foreach ($category->getFilter()->related()->orderBy('ordering')->get() as $filter)

                        @switch($filter->filterable_type)
                            @case('App\Models\Specification')

                                @php
                                    $spec_values = \DB::table('product_specification')->whereIn('product_id', $products_id)->where('specification_id', $filter->filterable_id)->get()->unique('value')->pluck('value')->toArray();
                                    $spec_values = get_separated_values($spec_values, $filter->separator);
                                @endphp

                                @if (count($spec_values))
                                    <div class="card w-100 d-flex align-items-center justify-content-center shadow overflow-hidden">
                                        <div class="card-header w-100 d-flex align-items-center justify-content-center" id="heading-{{ $loop->index }}">
                                            <h2 class="mb-0 w-100 d-flex align-items-center justify-content-center">
                                                <button class="btn btn-block text-right collapsed w-100 d-flex align-items-center justify-content-between p-0" type="button"
                                                    data-toggle="collapse" data-target="#collapse-{{ $loop->index }}"
                                                    aria-expanded="false" aria-controls="collapse-{{ $loop->index }}">
                                                    {{ $filter->filterable->name }}
                                                    <i class="mdi mdi-chevron-down"></i>
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="collapse-{{ $loop->index }}" class="collapse {{ request('filters.' . $filter->id) ? 'show' : '' }} w-100" aria-labelledby="heading-{{ $loop->index }}">
                                            <div class="card-body w-100">
                                                @foreach ($spec_values as $spec_val)
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input " name="filters[{{ $filter->id }}][{{ $spec_val }}]" {{ request('filters.' . $filter->id . '.' .$spec_val) ? 'checked' : '' }}
                                                            id="customCheck-{{ $loop->parent->index }}-{{ $loop->index }}">
                                                        <label class="custom-control-label"
                                                            for="customCheck-{{ $loop->parent->index }}-{{ $loop->index }}">{{ $spec_val }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @break
                            @case('App\Models\AttributeGroup')

                                @php
                                    $prices = \DB::table('prices')->whereIn('product_id', $products_id)->pluck('id');
                                    $attributes = \DB::table('attribute_price')->whereIn('price_id', $prices)->pluck('attribute_id');
                                    $group_values = \App\Models\Attribute::where('attribute_group_id', $filter->filterable_id)->whereIn('id', $attributes)->get();
                                @endphp

                                @if ($group_values->count())
                                    <div class="card w-100 d-flex align-items-center justify-content-center shadow overflow-hidden">
                                        <div class="card-header w-100 d-flex align-items-center justify-content-center" id="heading-{{ $loop->index }}">
                                            <h2 class="mb-0 w-100 d-flex align-items-center justify-content-center">
                                                <button class="btn btn-block text-right collapsed w-100 d-flex align-items-center justify-content-between p-0" type="button"
                                                    data-toggle="collapse" data-target="#collapse-{{ $loop->index }}"
                                                    aria-expanded="false" aria-controls="collapse-{{ $loop->index }}">
                                                    {{ $filter->filterable->name }}
                                                    <i class="mdi mdi-chevron-down"></i>
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="collapse-{{ $loop->index }}" class="collapse {{ request('filters.' . $filter->id) ? 'show' : '' }} w-100" aria-labelledby="heading-{{ $loop->index }}">
                                            <div class="card-body w-100">
                                                @foreach ($group_values as $group_val)
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input attribute_group_input " name="filters[{{ $filter->id }}][{{ $group_val->id }}]" {{ request('filters.' . $filter->id . '.' .$group_val->id) ? 'checked' : '' }}
                                                            id="customCheck-{{ $loop->parent->index }}-{{ $loop->index }}">
                                                        <label class="custom-control-label"
                                                            for="customCheck-{{ $loop->parent->index }}-{{ $loop->index }}">{{ $group_val->name }}</label>
                                                            @if ($group_val->group->type == "color")
                                                                <span class="filter-color" style="background-color: {{ $group_val->value }}"></span>
                                                            @endif
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @break
                            @case('App\Models\StaticFilter')
                                @include('front::products.partials.static-filters')
                                @break

                        @endswitch
                    @endforeach

                </div>
            </div>

            <div class="col-12 d-flex align-items-center justify-content-center">
                <button class="btn btn-info btn-block w-50 shadow btn-info_hover" type="submit">
                    فیلتر
                </button>
            </div>
        </form>
    </div>
</div>
