@extends('layouts.front')

@section('content')

<div class="container">
<h1>Notifications</h1>

<table class="table">
    <thead>
        <tr>
            <th>Notification</th>
            <th>Time</th>
            <th>Read At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($notifications as $notify)
        <tr class="{{ $notify->read()? '' : 'bg-info' }}">
            <td><a href="{{ route('notification.read', [$notify->id]) }}">{{ $notify->data['message'] }}</a></td>
            <td>{{ $notify->created_at->diffForHumans() }}</td>
            <td>{{ $notify->read_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>

@endsection