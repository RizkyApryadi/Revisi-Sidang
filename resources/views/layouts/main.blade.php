<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>@yield('title') | HKBP Soposurung</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

  <script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
    if (typeof tinymce !== 'undefined') {
      tinymce.init({
  selector: 'textarea.tinymce',
  license_key: 'gpl',
  height: 500,
  menubar: true,
  plugins: 'lists link image table code paste help wordcount media fullscreen emoticons advlist codesample',
  toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media emoticons codesample | code fullscreen',
  relative_urls: false,
  remove_script_host: false,
  convert_urls: true,
  image_caption: true,
  paste_as_text: true,
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});

    }
  });
  </script>

  {{-- Styling --}}
  @include('includes.style')

  @stack('style')
</head>

@stack('script')

<body>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      {{-- Navbar --}}
      @include('partials.nav')


      {{-- Sidebar --}}
      @include('partials.sidebar')

      <!-- Main Content -->
      <div class="main-content">
        @yield('content')
      </div>

      {{-- Footer --}}
      @include('partials.footer')
    </div>
  </div>

  {{-- Scripts --}}
  @include('includes.script')
  @stack('script')

</body>

</html>