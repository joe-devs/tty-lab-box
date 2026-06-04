@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 md:p-12">
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-extrabold tracking-tight mb-4">Job Opportunities</h1>
            <p class="text-slate-400">Choose a BashBox role and begin a realistic Linux administration work simulation.</p>
        </div>

        <div class="max-w-2xl mx-auto bg-slate-800 border border-slate-700 rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 md:p-8">
                <p class="text-xs uppercase tracking-widest text-slate-500 font-bold mb-3">CloudNova Hosting</p>
                <h2 class="text-3xl font-bold text-slate-100 mb-2">Junior Linux Administrator</h2>
                <p class="text-slate-400 mb-6">Join CloudNova Hosting for your first week on the infrastructure team and complete real Linux server work assigned by Julian.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm mb-8">
                    <div class="bg-slate-900/70 border border-slate-700 rounded-lg p-4">
                        <p class="text-slate-500 text-xs uppercase font-bold mb-1">Phase</p>
                        <p class="text-slate-200 font-semibold">First Week at Work</p>
                    </div>
                    <div class="bg-slate-900/70 border border-slate-700 rounded-lg p-4">
                        <p class="text-slate-500 text-xs uppercase font-bold mb-1">Manager</p>
                        <p class="text-slate-200 font-semibold">Julian</p>
                        <p class="text-slate-400 text-xs">Infrastructure Manager</p>
                    </div>
                    <div class="bg-slate-900/70 border border-slate-700 rounded-lg p-4">
                        <p class="text-slate-500 text-xs uppercase font-bold mb-1">Work</p>
                        <p class="text-slate-200 font-semibold">Live Server Tasks</p>
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('hiring.confirmation') }}"
                        class="inline-flex items-center justify-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-slate-900 bg-emerald-400 hover:bg-emerald-500 shadow-md transition-all">
                        Accept Role
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
