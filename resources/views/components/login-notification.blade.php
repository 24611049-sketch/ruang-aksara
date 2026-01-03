@if(session('login_notification'))
    <div id="loginNotification" class="fixed top-8 right-8 z-50 animate-fade-in">
        <!-- Toast Container -->
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden max-w-sm" style="box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 10px 10px -5px rgba(0, 0, 0, 0.1);">
            <!-- Header dengan gradient -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 flex items-center gap-3">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-full bg-white bg-opacity-20">
                        <i class="fas fa-sign-in-alt text-white text-lg"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">Selamat Datang!</h3>
                    <p class="text-sm text-green-100">{{ session('login_notification.message') }}</p>
                </div>
            </div>

            <!-- Body dengan user info -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center gap-3">
                    @php
                        $user = \Illuminate\Support\Facades\Auth::user();
                        $hasProfilePhoto = $user && ($user->foto_profil || $user->avatar);
                        $photoUrl = null;
                        
                        if ($hasProfilePhoto) {
                            if ($user->foto_profil && file_exists(public_path('storage/' . $user->foto_profil))) {
                                $photoUrl = asset('storage/' . $user->foto_profil);
                            } elseif ($user->avatar) {
                                $photoUrl = $user->avatar;
                            }
                        }
                    @endphp
                    
                    @if($photoUrl)
                        <!-- User Photo -->
                        <img src="{{ $photoUrl }}" alt="{{ session('login_notification.name') }}" 
                             class="h-10 w-10 rounded-full object-cover border-2 border-green-500 flex-shrink-0">
                    @else
                        <!-- Fallback Avatar with Initial -->
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 text-white font-semibold flex-shrink-0">
                            {{ strtoupper(substr(session('login_notification.name', 'U'), 0, 1)) }}
                        </div>
                    @endif
                    
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">{{ session('login_notification.name') }}</p>
                        <p class="text-xs text-gray-500">
                            @if(session('login_notification.role') === 'owner')
                                <i class="fas fa-crown text-yellow-500 mr-1"></i>Owner
                            @else
                                <i class="fas fa-shield-alt text-blue-500 mr-1"></i>Admin
                            @endif
                        </p>
                    </div>
                    <button type="button" onclick="closeLoginNotification()" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Progress bar -->
            <div class="h-1 bg-gradient-to-r from-green-600 to-emerald-600 animate-shrink"></div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(30px) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0) translateY(0);
            }
        }

        @keyframes shrink {
            from {
                width: 100%;
            }
            to {
                width: 0%;
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.4s ease-out;
        }

        .animate-shrink {
            animation: shrink 5s linear forwards;
        }
    </style>

    <script>
        // Auto-dismiss notification setelah 5 detik
        setTimeout(() => {
            closeLoginNotification();
        }, 5000);

        function closeLoginNotification() {
            const notification = document.getElementById('loginNotification');
            if (notification) {
                notification.style.animation = 'fadeIn 0.4s ease-out reverse';
                setTimeout(() => {
                    notification.remove();
                }, 400);
            }
        }
    </script>
@endif
