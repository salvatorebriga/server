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

    <!-- Scripts -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    <!-- Dark Mode Script -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const html = document.querySelector('html');
        const isLightOrAuto = localStorage.getItem('hs_theme') === 'light' || (localStorage.getItem('hs_theme') ===
          'auto' && !window.matchMedia('(prefers-color-scheme: dark)').matches);
        const isDarkOrAuto = localStorage.getItem('hs_theme') === 'dark' || (localStorage.getItem('hs_theme') ===
          'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches);

        if (isLightOrAuto && html.classList.contains('dark')) {
          html.classList.remove('dark');
        } else if (isDarkOrAuto && html.classList.contains('light')) {
          html.classList.remove('light');
        } else if (isDarkOrAuto && !html.classList.contains('dark')) {
          html.classList.add('dark');
        } else if (isLightOrAuto && !html.classList.contains('light')) {
          html.classList.add('light');
        }

        // Update icon and text in theme button
        updateThemeIcon();
      });

      function updateThemeIcon() {
        const html = document.querySelector('html');
        const themeIcon = document.getElementById('theme-icon');
        const themeText = document.getElementById('theme-text');

        if (html.classList.contains('dark')) {
          themeIcon.innerHTML = `<path d="M12 3a9 9 0 000 18c4.2 0 7.8-2.4 9-6a9 9 0 01-9-12z"></path>`;
          themeText.textContent = '{{ __('Dark Mode') }}';
        } else {
          themeIcon.innerHTML = `<circle cx="12" cy="12" r="4"></circle>`;
          themeText.textContent = '{{ __('Light Mode') }}';
        }
      }

      function toggleTheme() {
        const html = document.querySelector('html');
        if (html.classList.contains('dark')) {
          localStorage.setItem('hs_theme', 'light');
          html.classList.remove('dark');
          html.classList.add('light');
        } else {
          localStorage.setItem('hs_theme', 'dark');
          html.classList.remove('light');
          html.classList.add('dark');
        }
        updateThemeIcon();
      }
    </script>
  </head>

  <body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
      @include('layouts.navigation')

      <!-- Page Heading -->
      @if (isset($header))
        <header class="bg-white dark:bg-gray-800 shadow">
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
