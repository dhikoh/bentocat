<!DOCTYPE html>
<html lang="id" class="h-full bg-[#FCFBF9]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | BentoCat</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS v4 -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            background: radial-gradient(circle at top right, rgba(245, 158, 11, 0.05), transparent 40%),
                        radial-gradient(circle at bottom left, rgba(59, 130, 246, 0.03), transparent 40%),
                        #FCFBF9;
        }
        h1, button {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white border border-slate-200/80 p-8 rounded-3xl shadow-xl relative overflow-hidden">
        
        <!-- Decorative Glow -->
        <div class="absolute -top-10 -right-10 w-28 h-28 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -left-10 w-28 h-28 bg-blue-500/5 rounded-full blur-2xl pointer-events-none"></div>

        <div class="text-center mb-8 relative z-10">
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Login</h1>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST" class="space-y-5 relative z-10">
            @csrf
            <div>
                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       placeholder="admin@bentocat.id" 
                       class="w-full bg-slate-50 border border-slate-200 focus:border-amber-500 rounded-xl px-4 py-3 text-slate-800 placeholder:text-slate-450 focus:outline-none transition-all">
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Kata Sandi</label>
                </div>
                <input type="password" name="password" id="password" required 
                       placeholder="••••••••••••"
                       class="w-full bg-slate-50 border border-slate-200 focus:border-amber-500 rounded-xl px-4 py-3 text-slate-800 placeholder:text-slate-450 focus:outline-none transition-all">
            </div>

            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 text-sm text-slate-600 select-none cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-amber-500 focus:ring-0 bg-slate-50">
                    Ingat saya
                </label>
            </div>

            <button type="submit" 
                    class="w-full bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-bold text-sm tracking-wide py-3.5 rounded-xl shadow-lg shadow-amber-500/10 active:scale-[0.98] transition-all mt-4 flex items-center justify-center gap-2">
                <span>MASUK</span> 🐾
            </button>
        </form>
    </div>
</body>
</html>
