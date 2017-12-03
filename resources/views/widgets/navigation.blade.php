
@foreach($categories as $category)
    <li><a href="{{ route('category', $category->id) }}">{{ $category->name }}</a></li>
@endforeach