<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css'])    
        
        <style>
        .tag-item {
            display: inline-block;
            background: #e9ecef;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            margin: 0.25rem;
            font-size: 0.875rem;
        }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .step-section {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }
        
        .step-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .step-indicator {
            transition: all 0.3s ease;
        }

        .step-indicator.active {
            background-color: #2563eb;
            color: white;
        }

        .step-indicator.completed {
            background-color: #10b981;
            color: white;
        }

        .step-connector {
            transition: all 0.3s ease;
        }

        .step-connector.active {
            background-color: #2563eb;
        }
    </style>
     
        <!-- Scripts -->
        @vite(['resources/js/app.js'])
        <script type="module" src="{{ asset('build/js/job-form-new-fixed.js') }}"></script>
        <script type="module" src="{{ asset('build/js/job-description-editor.js') }}"></script>
        <!-- Thêm CKEditor CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.header')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>