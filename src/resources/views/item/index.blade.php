@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="heading">
    <div class="recommend">
        <h3>おすすめ</h3>
    </div>
    <a class="mylist" href="/mylist">
        <h3>マイリスト</h3>
    </a>
</div>
<div class="item-list">

    @foreach ($items as $item)
    <div class="item-card">
        <a href="/item/{{ $item['id'] }}">
            <img src="{{ '/storage/' . $item['image'] }}" alt=" 商品画像" class="item-image">
        </a>
        <div class="item-name">{{ $item->name }}</div>

    </div>
    @endforeach

</div>

<div class="pagination">
    {{ $items->links('vendor.pagination.semantic-ui') }}
</div>

@endsection