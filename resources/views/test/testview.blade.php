@extends('layouts.fe')

    <h3>Daftar Priceunit</h3>
    <table border="1">
        <tr>
            <th>No.</th>
            <th width="100px">Slug</th>
            <th width="200px">Name</th>
        </tr>
        @php
            $no = 1;
        @endphp
        @foreach($unitprice as $category)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $category->id_product }}</td>
                <td>{{ $category->units }}</td>
            </tr>
        @endforeach
    </table>




