@extends('auth.layouts.master')
@section('title', 'Заказы')
@section('content')

    <div id="app">
        <div class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <h2>Заказы</h2>
                        <br>
                        <table class="table">
                            <tbody>
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>
                                    Имя
                                </th>
                                <th>
                                    Телефон
                                </th>
                                <th>
                                    Когда отправлен
                                </th>
                                <th>
                                    Сумма
                                </th>
                                <th>
                                    Действия
                                </th>
                            </tr>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{$order->id}}</td>
                                    <td>{{$order->name}}</td>
                                    <td>{{$order->phone}}</td>
                                    <td>{{$order->created_at->format('H:i d/m/Y')}}</td>
                                    <td>{{$order->getFullPrice()}}</td>
                                    <td>
                                        <div>
                                            <a class="btn btn-success" type="button" href="#">Открыть</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
