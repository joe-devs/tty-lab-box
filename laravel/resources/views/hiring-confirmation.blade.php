@extends('layouts.app')

@section('content')
    <div class="container mx-auto w-full px-6 py-10 md:py-14">
        <div class="mx-auto max-w-5xl">
            <div class="mb-8 max-w-3xl">
                <p class="mb-3 text-xs font-bold uppercase tracking-widest text-emerald-300">Hiring Confirmation</p>
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight text-slate-100 md:text-5xl">Welcome to CloudNova Hosting</h1>
                <p class="max-w-2xl text-slate-400">Your first-week Linux administration role is ready inside BashBox.</p>
            </div>

            <div class="overflow-hidden rounded-lg border border-slate-700 bg-slate-800/80 shadow-2xl shadow-black/20">
                <div class="h-1 bg-gradient-to-r from-blue-500 via-emerald-400 to-teal-400"></div>
                <div class="grid lg:grid-cols-[1fr_18rem]">
                    <div class="p-6 md:p-8">
                        <div class="mb-5 flex flex-wrap items-center gap-3">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-500">CloudNova Hosting</p>
                            <span class="rounded-md border border-emerald-400/20 bg-emerald-400/10 px-2.5 py-1 text-xs font-semibold text-emerald-300">Role Accepted</span>
                        </div>

                        <h2 class="mb-3 text-3xl font-bold text-slate-100">Junior Linux Administrator</h2>
                        <p class="mb-8 max-w-2xl text-slate-400">You joined CloudNova Hosting as a Junior Linux Administrator. Julian has prepared your first-week work assignments in your Employee Workspace.</p>

                        <dl class="grid gap-px overflow-hidden rounded-lg border border-slate-700 bg-slate-700 text-sm md:grid-cols-3">
                            <div class="bg-slate-900/70 p-4">
                                <dt class="mb-1 text-xs font-bold uppercase tracking-wide text-slate-500">Phase</dt>
                                <dd class="font-semibold text-slate-200">First Week at Work</dd>
                            </div>
                            <div class="bg-slate-900/70 p-4">
                                <dt class="mb-1 text-xs font-bold uppercase tracking-wide text-slate-500">Manager</dt>
                                <dd class="font-semibold text-slate-200">Julian</dd>
                                <dd class="mt-0.5 text-xs text-slate-400">Infrastructure Manager</dd>
                            </div>
                            <div class="bg-slate-900/70 p-4">
                                <dt class="mb-1 text-xs font-bold uppercase tracking-wide text-slate-500">Workspace</dt>
                                <dd class="font-semibold text-slate-200">Employee Workspace</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="border-t border-slate-700 bg-slate-900/70 p-6 lg:border-l lg:border-t-0 md:p-8">
                        <p class="mb-2 text-xs font-bold uppercase tracking-widest text-slate-500">Next Step</p>
                        <p class="mb-6 text-sm leading-6 text-slate-300">Open your Employee Workspace to review the work Julian assigned for your first week.</p>
                        <a href="{{ route('home') }}"
                            class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-emerald-400 px-6 py-2.5 text-sm font-bold text-slate-950 shadow-md shadow-emerald-950/20 transition-all hover:bg-emerald-300">
                            Open Employee Workspace
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
