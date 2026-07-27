<?php

namespace App\Support;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Parses @mentions and #hashtags out of user-authored post/comment text,
 * and turns them into links at render time.
 */
class ContentParser
{
    /** Usernames are what Profile.username allows. */
    private const MENTION_PATTERN = '/(^|[^\w\/@])@([A-Za-z0-9_]{1,30})\b/';

    /**
     * The leading [^\w&] guard matters: e() turns apostrophes into &#039;
     * and without it "#039" would be picked up as a hashtag.
     */
    private const HASHTAG_PATTERN = '/(^|[^\w&#])#([A-Za-z0-9_]{1,50})\b/';

    /**
     * Usernames mentioned in the text, lowercased and de-duplicated.
     *
     * @return array<int, string>
     */
    public static function extractMentions(?string $text): array
    {
        if (! $text) {
            return [];
        }

        preg_match_all(self::MENTION_PATTERN, $text, $matches);

        return array_values(array_unique(array_map('strtolower', $matches[2] ?? [])));
    }

    /**
     * Hashtag names in the text, lowercased and de-duplicated.
     *
     * @return array<int, string>
     */
    public static function extractHashtags(?string $text): array
    {
        if (! $text) {
            return [];
        }

        preg_match_all(self::HASHTAG_PATTERN, $text, $matches);

        return array_values(array_unique(array_map('strtolower', $matches[2] ?? [])));
    }

    /**
     * Resolve mentioned usernames to real users, excluding the author.
     */
    public static function mentionedUsers(?string $text, ?int $excludeUserId = null): Collection
    {
        $usernames = self::extractMentions($text);

        if (! $usernames) {
            return collect();
        }

        return Profile::whereIn('username', $usernames)
            ->with('user')
            ->get()
            ->pluck('user')
            ->filter()
            ->reject(fn (User $user) => $excludeUserId && $user->id === $excludeUserId)
            ->unique('id')
            ->values();
    }

    /**
     * Turn @mentions and #hashtags in already-rendered HTML into links.
     *
     * Takes HTML (post-markdown) rather than raw text so the caller keeps its
     * existing escaping. Content inside <code>/<pre> is left completely alone —
     * on a developer platform those legitimately contain things like Python
     * decorators (@app.route) and CSS id selectors (#main).
     */
    public static function linkify(?string $html): string
    {
        if (! $html) {
            return '';
        }

        // Stash code blocks behind placeholders containing no @ or # so the
        // patterns below cannot match inside them.
        $stash = [];
        $html = preg_replace_callback(
            '/<(pre|code)\b[^>]*>.*?<\/\1>/is',
            function (array $m) use (&$stash) {
                $key = '___DEVDOKO_CODE_'.count($stash).'___';
                $stash[$key] = $m[0];

                return $key;
            },
            $html
        );

        // The (?![^<>]*>) guard skips matches sitting inside a tag's attributes.
        $html = preg_replace_callback(
            '/(^|[^\w\/@])@([A-Za-z0-9_]{1,30})\b(?![^<>]*>)/',
            fn (array $m) => $m[1].'<a href="'.url('/@'.$m[2]).'" class="mention-link">@'.$m[2].'</a>',
            $html
        );

        $html = preg_replace_callback(
            '/(^|[^\w&#])#([A-Za-z0-9_]{1,50})\b(?![^<>]*>)/',
            fn (array $m) => $m[1].'<a href="'.url('/tags/'.strtolower($m[2])).'" class="hashtag-link">#'.$m[2].'</a>',
            $html
        );

        return $stash ? strtr($html, $stash) : $html;
    }
}
