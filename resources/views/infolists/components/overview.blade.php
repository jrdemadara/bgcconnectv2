<div {{ $attributes }}>
 

  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: radial-gradient(ellipse at top left, #1f2937 0%, #111827 100%);
    }
    .glass {
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
    }
    .pulse {
      animation: pulse 3s ease-in-out infinite;
    }
    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.05);
      }
    }
  </style>

<body class="min-h-screen flex items-center justify-center text-white font-sans">
  <div class="max-w-5xl w-full p-8 rounded-2xl glass shadow-2xl ring-1 ring-white/10 space-y-10">
    
    <!-- Profile Header -->
    <div class="flex flex-col items-center text-center space-y-4">
      <div class="relative">
        <img src="https://i.pravatar.cc/150?img=12" alt="Avatar" class="w-32 h-32 rounded-full border-4 border-white shadow-xl">
        <span class="absolute bottom-0 right-0 bg-green-500 w-6 h-6 rounded-full ring-2 ring-white"></span>
      </div>
      <div>
        <h1 class="text-3xl font-bold tracking-wide">Johnny Roger Demadara</h1>
        <span class="inline-flex items-center gap-2 bg-green-700/20 text-green-400 text-sm px-4 py-1 rounded-full mt-1">
          <svg class="w-4 h-4 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
            <path d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l7-7a1 1 0 000-1.414z" />
          </svg>
          Verified Elite Member
        </span>
      </div>
    </div>

    <!-- Profile Completion -->
    <div class="space-y-2">
      <div class="flex items-center justify-between">
        <span class="text-sm text-gray-300">Profile Completion</span>
        <span class="text-sm font-medium text-green-400">25%</span>
      </div>
      <div class="w-full bg-gray-700 h-3 rounded-full overflow-hidden shadow-inner">
        <div class="bg-gradient-to-r from-green-400 via-yellow-300 to-red-500 h-full rounded-full w-[50%] animate-pulse"></div>
      </div>
      <p class="text-xs text-gray-400 text-right italic">Complete your profile to unlock achievements!</p>
    </div>

    <!-- CTA Alert -->
    <div class="bg-gradient-to-br from-orange-500 via-yellow-400 to-pink-500 text-black px-6 py-4 rounded-xl shadow-lg flex justify-between items-center animate-pulse">
      <div class="flex items-center gap-3">
        <span class="text-2xl">🔥</span>
        <span class="font-semibold">Complete your profile to reach <span class="underline">Level 2</span> and earn a <span class="font-bold">+20 bonus!</span></span>
      </div>
      <a href="#" class="bg-black/80 hover:bg-black text-white px-4 py-2 rounded-md text-sm transition">Go Now</a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      
      <!-- Points -->
      <div class="glass p-6 rounded-xl flex flex-col justify-between border border-green-400/20 pulse shadow-lg hover:scale-105 transition">
        <div class="flex justify-between items-center">
          <div class="text-green-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M12 8c-1.657 0-3 1.567-3 3.5S10.343 15 12 15s3-1.567 3-3.5S13.657 8 12 8z"/>
              <path d="M12 2C6.48 2 2 6.48 2 12a10 10 0 0017.514 6.62L22 22"/>
            </svg>
          </div>
          <div class="text-3xl font-bold text-white">7,490</div>
        </div>
        <p class="mt-2 text-sm text-green-400 flex items-center gap-1">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 10l5-5 5 5H5z"/></svg>
          +0% than last month
        </p>
      </div>

      <!-- Referrals -->
      <div class="glass p-6 rounded-xl flex flex-col justify-between border border-blue-400/20 shadow-lg hover:scale-105 transition">
        <div class="flex justify-between items-center">
          <div class="text-blue-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M17 20h5v-2a4 4 0 00-5-4"/>
              <circle cx="9" cy="7" r="4"/>
              <path d="M3 21v-2a4 4 0 014-4h4"/>
            </svg>
          </div>
          <div class="text-3xl font-bold text-white">10</div>
        </div>
        <p class="mt-2 text-sm text-green-400 flex items-center gap-1">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 10l5-5 5 5H5z"/></svg>
          +0% than last month
        </p>
      </div>

      <!-- Downlines -->
      <div class="glass p-6 rounded-xl flex flex-col justify-between border border-indigo-400/20 shadow-lg hover:scale-105 transition">
        <div class="flex justify-between items-center">
          <div class="text-indigo-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M8 17l4 4 4-4m0-5l-4-4-4 4"/>
            </svg>
          </div>
          <div class="text-3xl font-bold text-white">21,375</div>
        </div>
        <p class="mt-2 text-sm text-red-400 flex items-center gap-1">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M15 10l-5 5-5-5h10z"/></svg>
          -100% than last month
        </p>
      </div>

      <!-- Activity -->
      <div class="glass p-6 rounded-xl flex flex-col justify-between border border-pink-400/20 shadow-lg hover:scale-105 transition">
        <div class="flex justify-between items-center">
          <div class="text-pink-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M8 7V3M16 7V3M3 11h18M5 19h14a2 2 0 002-2v-5H3v5a2 2 0 002 2z"/>
            </svg>
          </div>
          <div class="text-3xl font-bold text-white">1</div>
        </div>
        <p class="mt-2 text-sm text-green-300 flex items-center gap-1">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 10l5-5 5 5H5z"/></svg>
          0% than last month
        </p>
      </div>
    </div>

    <!-- Footer or extra section -->
    <div class="text-center text-sm text-gray-400 mt-6 italic">
      🚀 Keep going — the next level unlocks exclusive rewards!
    </div>
  </div>
</body>
</html>

    {{ $getChildComponentContainer() }}
</div>
