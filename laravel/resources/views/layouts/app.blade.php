<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BashBox</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    @stack('styles')
</head>

<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col font-sans">
    <header class="bg-slate-900/95 border-b border-slate-700/80 px-4 py-3 shrink-0 shadow-sm">
        <div class="container mx-auto flex flex-wrap gap-3 justify-between items-center">
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('jobs') }}"
                    class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-emerald-400">
                    BashBox
                </a>
                <span
                    class="hidden sm:inline-flex items-center rounded-md border border-emerald-400/20 bg-emerald-400/10 px-2.5 py-1 text-xs font-semibold text-emerald-300">
                    Job Simulation Platform
                </span>
            </div>
        </div>
    </header>

    <main class="flex-grow flex flex-col relative">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>
