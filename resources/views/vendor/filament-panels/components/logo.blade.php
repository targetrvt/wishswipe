<div x-data="{ isDark: false }" x-init="isDark = document.documentElement.classList.contains('dark') || document.body.classList.contains('dark'); setInterval(() => { isDark = document.documentElement.classList.contains('dark') || document.body.classList.contains('dark'); }, 100)">
    <img 
        x-show="!isDark"
        src="{{ asset('images/wishswipe_logo_black.png') }}" 
        alt="WishSwipe Logo"
        class="h-16"
    />
    <img 
        x-show="isDark"
        src="{{ asset('images/wishSwipe_logo2.png') }}" 
        alt="WishSwipe Logo"
        class="h-16"
    />
</div>
