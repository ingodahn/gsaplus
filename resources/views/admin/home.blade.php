@extends('layouts.master')
@section('title', 'Backend')

@section('content')
    <div class="container">

        <h2>Backend</h2>
        <p>Dies ist das provisorische Administratoren-Backend.</p>
		<ul>
			<li><a href="/AdminCodes">Codes</a></li>
			<li><a href="/AdminPatients">Patienten</a></li>
			<li><a href="/Logout">Ausloggen.</a></li>
		</ul>

    </div>
@endsection
