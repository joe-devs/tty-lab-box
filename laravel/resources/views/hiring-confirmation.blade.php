@extends('layouts.app')

@section('content')
    <div class="flex-grow flex items-center justify-center p-6">
        <div class="bg-slate-800 border border-slate-700 p-8 rounded-xl shadow-lg max-w-xl w-full text-center">
            <p class="text-xs uppercase tracking-widest text-slate-500 font-bold mb-3">Hiring Confirmation</p>
            <h1 class="text-3xl font-extrabold tracking-tight mb-4">Welcome to CloudNova Hosting</h1>
            <p class="text-slate-400 mb-2">You joined CloudNova Hosting as a Junior Linux Administrator.</p>
            <p class="text-slate-400 mb-8">Julian, Infrastructure Manager, has prepared your first-week work assignments in your Employee Workspace.</p>

            <a href="{{ route('home') }}"
                class="inline-flex items-center justify-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-slate-900 bg-emerald-400 hover:bg-emerald-500 shadow-md transition-all">
                Open Employee Workspace
            </a>
        </div>
    </div>
@endsection
