        {{-- jQuery (REQUIRED) --}}
        @if (!isset ($jquery) || (isset($jquery) && $jquery == true))
        @basset('https://unpkg.com/jquery@3.6.4/dist/jquery.min.js')
        @endif

        {{-- jQuery UI and Smoothness theme --}}
        @basset('https://code.jquery.com/ui/1.13.2/jquery-ui.min.js')
        @basset('https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.min.css')

        {{-- elFinder JS (REQUIRED) --}}

        <script src="{{asset('assets/elfinder/elfinder.min.js')}}"></script>

        {{-- elFinder translation (OPTIONAL) --}}
        @if($locale)
        @basset('https://cdnjs.cloudflare.com/ajax/libs/elfinder/2.1.62/js/i18n/elfinder.'.$locale.'.min.js')
        @endif

        {{-- elFinder sounds --}}
        @basset(base_path('vendor/studio-42/elfinder/sounds/rm.wav'))
