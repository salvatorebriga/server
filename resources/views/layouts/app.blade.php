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
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dark Mode Script -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const html = document.querySelector('html');
        const isLightOrAuto = localStorage.getItem('hs_theme') === 'light' || (localStorage.getItem(
            'hs_theme') ===
          'auto' && !window.matchMedia('(prefers-color-scheme: dark)').matches);
        const isDarkOrAuto = localStorage.getItem('hs_theme') === 'dark' || (localStorage.getItem(
            'hs_theme') ===
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
          themeIcon.innerHTML = ` <circle cx="12" cy="12" r="4"></circle>
                                        <path d="M12 2v2"></path>
                                        <path d="M12 20v2"></path>
                                        <path d="m4.93 4.93 1.41 1.41"></path>
                                        <path d="m17.66 17.66 1.41 1.41"></path>
                                        <path d="M2 12h2"></path>
                                        <path d="M20 12h2"></path>
                                        <path d="m6.34 17.66-1.41 1.41"></path>
                                        <path d="m19.07 4.93-1.41 1.41"></path>`;
          themeText.textContent = '{{ __('Light Mode') }}';
        } else {
          themeIcon.innerHTML = `<path d="M12 3a9 9 0 000 18c4.2 0 7.8-2.4 9-6a9 9 0 01-9-12z"></path>`;
          themeText.textContent = '{{ __('Dark Mode') }}';
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

      //TomTom Autocomplete
      function getAutocomplete() {
        //value dell'input
        const query = document.getElementById('address').value;

        if (query.length < 4) {
          document.getElementById('results').innerHTML = '';
          return;
        }

        fetch(`/autocomplete?query=${query}`, {
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          })
          .then(response => response.json())
          .then(data => {
            const resultsContainer = document.getElementById('results');
            resultsContainer.innerHTML = '';

            data.forEach(result => {
              const li = document.createElement('li');
              li.classList.add('p-2', 'cursor-pointer', 'hover:bg-gray-100',
                'dark:hover:bg-indigo-600');
              li.textContent = result.address.freeformAddress;
              // Aggiungi event listener per inserire il testo selezionato nell'input
              li.addEventListener('click', function() {
                document.getElementById('address').value = result.address
                  .freeformAddress;
                resultsContainer.innerHTML = ''; // Pulire la lista dopo aver selezionato
              });
              resultsContainer.appendChild(li);

            });

          })
          .catch(error => console.error('Errore:', error));
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
