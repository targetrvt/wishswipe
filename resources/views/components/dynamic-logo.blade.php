@props([
    'class' => 'h-16',
    'alt' => 'WishSwipe Logo',
    'style' => ''
])

{{-- Show black logo in light mode, white logo in dark mode --}}
<img 
    src="{{ asset('images/wishswipe_logo_black.png') }}" 
    alt="{{ $alt }}"
    class="{{ $class }} light-mode-logo"
    style="{{ $style }}"
/>
<img 
    src="{{ asset('images/wishSwipe_logo2.png') }}" 
    alt="{{ $alt }}"
    class="{{ $class }} dark-mode-logo"
    style="{{ $style }} display: none;"
/>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dynamic logo script loaded');
    
    // Function to update logo based on theme
    function updateLogo() {
        console.log('updateLogo called');
        
        // Find all logo pairs on the page
        const lightModeLogos = document.querySelectorAll('.light-mode-logo');
        const darkModeLogos = document.querySelectorAll('.dark-mode-logo');
        
        console.log('Found light mode logos:', lightModeLogos.length);
        console.log('Found dark mode logos:', darkModeLogos.length);
        
        if (lightModeLogos.length === 0 || darkModeLogos.length === 0) {
            console.log('No logos found, skipping update');
            return;
        }
        
        // Check if dark mode is active - simplified approach
        // Filament uses Tailwind's dark mode, so we check for the 'dark' class on html
        const isDarkMode = document.documentElement.classList.contains('dark');
        
        console.log('HTML element classes:', document.documentElement.className);
        console.log('Has dark class on html?', document.documentElement.classList.contains('dark'));
        
        console.log('Is dark mode?', isDarkMode);
        console.log('HTML classes:', document.documentElement.className);
        console.log('Body classes:', document.body.className);
        console.log('HTML data-theme:', document.documentElement.getAttribute('data-theme'));
        console.log('Body data-theme:', document.body.getAttribute('data-theme'));
        
        if (isDarkMode) {
            // Dark mode: show white logo (wishSwipe_logo2.png)
            lightModeLogos.forEach(logo => logo.style.display = 'none');
            darkModeLogos.forEach(logo => logo.style.display = 'block');
            console.log('Dark mode active - showing white logo');
        } else {
            // Light mode: show black logo (wishswipe_logo_black.png)
            lightModeLogos.forEach(logo => logo.style.display = 'block');
            darkModeLogos.forEach(logo => logo.style.display = 'none');
            console.log('Light mode active - showing black logo');
        }
    }
    
    // Initial update
    updateLogo();
    
    // Listen for theme changes with more comprehensive detection
    const observer = new MutationObserver(function(mutations) {
        console.log('Mutation observed:', mutations);
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && 
                (mutation.attributeName === 'class' || 
                 mutation.attributeName === 'data-theme' ||
                 mutation.attributeName === 'data-color-scheme')) {
                console.log('Theme change detected, updating logo');
                updateLogo();
            }
        });
    });
    
    // Observe changes to html and body classes and attributes
    observer.observe(document.documentElement, { 
        attributes: true, 
        attributeFilter: ['class', 'data-theme', 'data-color-scheme'] 
    });
    observer.observe(document.body, { 
        attributes: true, 
        attributeFilter: ['class', 'data-theme', 'data-color-scheme'] 
    });
    
    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', updateLogo);
    
    // Also listen for any custom theme change events
    document.addEventListener('theme-changed', updateLogo);
    window.addEventListener('theme-changed', updateLogo);
    
    // Periodic check as fallback
    setInterval(updateLogo, 2000);
});
</script>
