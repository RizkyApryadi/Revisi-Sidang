<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <title>Sistem Informasi Gereja</title>

  <style>
    .main-container {
      width: 950px;
      height: 550px;
      background: white;
      border-radius: 16px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
      overflow: hidden;
    }

    .church-gradient {
      background: linear-gradient(to bottom right, #1e3a8a, #6366f1);
      color: white;
    }
  </style>
</head>

<body class="flex items-center justify-center h-screen bg-gray-200">

  <div class="main-container flex">

    <!-- PANEL KIRI -->
    <div class="w-1/2 church-gradient flex flex-col items-center justify-center px-10 text-center">
      <i class="fa-solid fa-cross text-6xl mb-6"></i>
      <h1 class="text-3xl font-bold mb-2">SISTEM INFORMASI</h1>
      <h2 class="text-2xl font-semibold mb-4">GEREJA</h2>
      <p class="text-sm opacity-90 leading-relaxed">
        Melayani dengan kasih, mengelola data jemaat, pelayanan,
        dan kegiatan gereja secara terintegrasi.
      </p>
    </div>

    <!-- PANEL KANAN (LOGIN) -->
    <div class="w-1/2 flex flex-col justify-center px-12">

      <h2 class="text-3xl font-bold text-gray-800 mb-2 text-center">
        Login Pelayanan
      </h2>
      <p class="text-center text-gray-500 mb-8">
        Silakan masuk untuk melanjutkan
      </p>

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-4 relative">
          <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
          <input
            id="email"
            type="text"
            name="email"
            placeholder="Email / Username"
            value="{{ old('email') }}"
            required
            autofocus
            class="w-full pl-10 pr-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror">

          @error('email')
          <span class="text-sm text-red-500">
            {{ $message }}
          </span>
          @enderror
        </div>

        <!-- Password -->
        <div class="mb-6 relative">
          <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
          <input
            id="password"
            type="password"
            name="password"
            placeholder="Password"
            required
            class="w-full pl-10 pr-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('password') border-red-500 @enderror">

          @error('password')
          <span class="text-sm text-red-500">
            {{ $message }}
          </span>
          @enderror
        </div>

        <!-- Button -->
        <button
          type="submit"
          class="w-full py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition duration-200">
          <i class="fa-solid fa-right-to-bracket mr-2"></i>
          Masuk
        </button>

      </form>

      <p class="text-center text-sm text-gray-400 mt-6">
        © {{ date('Y') }} Sistem Informasi Gereja
      </p>

    </div>
  </div>

</body>

</html>
