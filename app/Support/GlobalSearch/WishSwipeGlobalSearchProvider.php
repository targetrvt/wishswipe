<?php

namespace App\Support\GlobalSearch;

use Filament\GlobalSearch\Contracts\GlobalSearchProvider;
use Filament\GlobalSearch\GlobalSearchResult;
use Filament\GlobalSearch\GlobalSearchResults;

class WishSwipeGlobalSearchProvider implements GlobalSearchProvider
{
    public function getResults(string $query): ?GlobalSearchResults
    {
        $query = trim($query);
        if ($query === '') {
            return null;
        }

        $q = mb_strtolower($query);

        $locale = app()->getLocale();

        // Safe URL generator to prevent exceptions if routes aren't bound yet
        $safe = function (callable $cb): string {
            try {
                return (string) $cb();
            } catch (\Throwable $e) {
                return url('/app');
            }
        };

        $pages = [
            [
                'label' => __('dashboard.navigation_label'),
                'url' => $safe(fn () => \App\Filament\Pages\Dashboard::getUrl(panel: 'app')),
                'keywords_en' => ['dashboard', 'home', 'panel'],
                'keywords_lv' => ['panelis', 'sākums', 'sakums'],
            ],
            [
                'label' => __('discover.navigation_label'),
                'url' => $safe(fn () => \App\Filament\Pages\SwipingPage::getUrl(panel: 'app')),
                'keywords_en' => ['discover', 'swipe', 'swiping'],
                'keywords_lv' => ['atklāt', 'atklat', 'velšana', 'velsana'],
            ],
            [
                'label' => __('listings.navigation_label'),
                'url' => $safe(fn () => \App\Filament\Pages\MyListings::getUrl(panel: 'app')),
                'keywords_en' => ['listings', 'my listings', 'products'],
                'keywords_lv' => ['mani sludinājumi', 'sludinājumi', 'sludinajumi', 'produkti'],
            ],
            [
                'label' => __('conversations.navigation_label'),
                'url' => $safe(fn () => \App\Filament\Pages\ConversationsPage::getUrl(panel: 'app')),
                'keywords_en' => ['messages', 'conversations', 'chat', 'chats'],
                'keywords_lv' => ['sarunas', 'ziņojumi', 'zinojumi', 'čats', 'cats'],
            ],
        ];

        $builder = GlobalSearchResults::make();

        // Allow single-letter queries; still trim whitespace
        $minLen = 1;

        $matches = function (string $needle, string $haystack) use ($minLen): bool {
            $needle = trim(mb_strtolower($needle));
            $haystack = mb_strtolower($haystack);
            if (mb_strlen($needle) < $minLen) {
                return false;
            }
            // Match anywhere within the label/keyword for the active locale
            return str_contains($haystack, $needle);
        };

        $matched = collect($pages)
            ->filter(function (array $page) use ($q, $locale, $matches) {
                $label = (string) $page['label'];
                if ($matches($q, $label)) {
                    return true;
                }
                $keywords = $locale === 'lv' ? ($page['keywords_lv'] ?? []) : ($page['keywords_en'] ?? []);
                foreach ($keywords as $kw) {
                    if ($matches($q, $kw)) {
                        return true;
                    }
                }
                return false;
            })
            ->map(function (array $page) {
                return new GlobalSearchResult(
                    title: (string) $page['label'],
                    url: (string) $page['url'],
                    details: [],
                    actions: [],
                );
            });

        if ($matched->isEmpty()) {
            return $builder; // empty results -> Filament shows "No search results found."
        }

        // Put all page results under a single category
        $builder->category('Search results', $matched);

        return $builder;
    }
}


