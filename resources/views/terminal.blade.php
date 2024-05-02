@extends('layouts.app')

@section('content')
<div class="terminal-window">
    <textarea id="terminal" readonly></textarea>
    <input type="text" id="command-input" placeholder="Enter command..." autofocus />
</div>
@endsection
