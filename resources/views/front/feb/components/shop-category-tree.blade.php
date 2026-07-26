@foreach($categoryItems as $categoryItem)
    @php
        $categoryValue = $categoryItem->category_slug ?: $categoryItem->id;
        $isSelected = optional($selectedCategory)->id === $categoryItem->id;
        $isInActivePath = $activeCategoryPath->contains($categoryItem->id);
    @endphp
    <li class="shop-category-node {{ $isInActivePath ? 'is-open' : '' }}">
        <a href="{{ route('shop-new', ['category' => $categoryValue]) }}"
           class="shop-category-link {{ $isSelected ? 'is-active' : '' }}">
            <span>{{ $categoryItem->category_name }}</span>
            <span class="facet-count">{{ $categoryItem->total_products_count }}</span>
        </a>

        @if($categoryItem->children->isNotEmpty())
            <ul class="shop-subcategory-list">
                @include('front.feb.components.shop-category-tree', [
                    'categoryItems' => $categoryItem->children,
                    'selectedCategory' => $selectedCategory,
                    'activeCategoryPath' => $activeCategoryPath,
                ])
            </ul>
        @endif
    </li>
@endforeach
