@extends('layouts.app')

@section('content')
    <h1>Products</h1>
    <div>
        <h2>search</h2>
        <form action="/home" method="GET">
    <input type="text" name="name" placeholder="Search product name">
    <button type="submit">Confirm</button>
</form>

    <div>
        <h2>search</h2>
        <form action="/home" method="POST">
            @csrf
    <input type="text" name="name">
    <button type="submit">Confirm</button>
</form>

    </div>

    @foreach($brands as $brand)
                    <span>{{ $brand->name }}</span>
                  
                @endforeach

    @if($products->count())
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>${{ $product->price }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No products found.</p>
    @endif
@endsection
