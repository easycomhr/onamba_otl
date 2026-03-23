<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'OTMS')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        blue:  { 50:'#eff6ff',600:'#2563eb',700:'#1d4ed8',800:'#1e40af' },
                        green: { 50:'#f0fdf4',100:'#dcfce3',600:'#16a34a',700:'#15803d' },
                        gray:  { 50:'#f9fafb',100:'#f3f4f6',200:'#e5e7eb',300:'#d1d5db',500:'#6b7280',600:'#4b5563',800:'#1f2937' },
                        red:   { 50:'#fef2f2',500:'#ef4444' },
                        yellow:{ 50:'#fefce8',100:'#fef9c3',600:'#ca8a04',700:'#a16207',800:'#854d0e' },
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-200 flex justify-center min-h-screen font-sans text-[16px]">

<div class="w-full max-w-md bg-gray-50 min-h-screen md:min-h-[850px] md:rounded-[2.5rem] md:shadow-2xl flex flex-col relative overflow-hidden md:my-8">

    @yield('content')
</div>

@stack('modals')
@stack('scripts')
</body>
</html>