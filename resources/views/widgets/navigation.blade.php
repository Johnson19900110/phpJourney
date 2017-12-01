@foreach($categories as $category)
    <li><a href="{{ route('category', $category->id) }}">{{ $category->category_name }}</a></li>
@endforeach