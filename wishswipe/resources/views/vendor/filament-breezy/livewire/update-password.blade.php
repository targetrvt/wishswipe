<x-filament-breezy::grid-section md=2 :title="__('filament-breezy::default.profile.password.heading')" :description="__('filament-breezy::default.profile.password.subheading')">
    <x-filament::card>
        <form wire:submit.prevent="submit" class="space-y-6" x-data id="password-update-form">

            {{ $this->form }}

            <div class="text-right">
                <x-filament::button type="submit" form="submit" class="align-right">
                    {{ __('filament-breezy::default.profile.password.submit.label') }}
                </x-filament::button>
            </div>
            <script>
                (function(){
                    function enhancePasswordReveal(root){
                        if(!root) return;
                        const inputs = root.querySelectorAll('input[type="password"]');
                        inputs.forEach((input) => {
                            if (input.dataset.revealEnhanced === '1') return;
                            input.dataset.revealEnhanced = '1';

                            const wrapper = document.createElement('div');
                            wrapper.className = 'relative';
                            const parent = input.parentNode;
                            if (!parent) return;
                            parent.insertBefore(wrapper, input);
                            wrapper.appendChild(input);

                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.setAttribute('aria-label', 'Show password');
                            btn.className = 'absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none';
                            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M12 5c-4.5 0-8.4 2.6-10 7 1.6 4.4 5.5 7 10 7s8.4-2.6 10-7c-1.6-4.4-5.5-7-10-7Zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10Zm0-2.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" /></svg>';

                            const reveal = () => { try { input.type = 'text'; } catch(e) {} };
                            const conceal = () => { try { input.type = 'password'; } catch(e) {} };

                            btn.addEventListener('mousedown', (e) => { e.preventDefault(); reveal(); });
                            btn.addEventListener('mouseup', conceal);
                            btn.addEventListener('mouseleave', conceal);
                            btn.addEventListener('touchstart', (e) => { e.preventDefault(); reveal(); }, { passive: false });
                            btn.addEventListener('touchend', conceal);
                            btn.addEventListener('touchcancel', conceal);
                            btn.addEventListener('blur', conceal);

                            wrapper.appendChild(btn);
                            input.classList.add('pr-10');
                        });
                    }

                    function run(){
                        const form = document.getElementById('password-update-form');
                        enhancePasswordReveal(form);
                    }

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', run);
                    } else {
                        run();
                    }

                    if (window.Livewire && window.Livewire.hook) {
                        window.Livewire.hook('message.processed', run);
                    }
                })();
            </script>
        </form>
    </x-filament::card>
</x-filament-breezy::grid-section>
