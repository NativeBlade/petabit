<?php

namespace App\Support;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

/**
 * Localizes the structured evolution changes the server emits when a pet grows.
 * The server sends data, not prose, so the label re-localizes to the user's
 * language: a `type` picks the template (`data.change.<type>`) and the `value`
 * resolves via gene_value (parts/colors), pattern (coat) or mark.
 */
class GenomeLabel
{
    /**
     * Localize a structured evolution change from the server, e.g.
     *   ['type' => 'ears', 'value' => 'cat']  ->  "orelhas: Gato"
     *   ['type' => 'eyes', 'count' => 2]      ->  "olhos +1 (2)"
     */
    public static function change(array|string $change): string
    {
        if (is_string($change)) {
            return $change; // backward-compat with old plain-string changes
        }

        $type = (string) ($change['type'] ?? '');
        $value = $change['value'] ?? null;

        $valueText = '';
        if ($value !== null && $value !== '') {
            $ns = match ($type) {
                'coat' => 'data.pattern.',
                'mark' => 'data.mark.',
                default => 'data.gene_value.',
            };
            $valueText = Lang::has($ns.$value) ? __($ns.$value) : Str::headline((string) $value);
        }

        $tpl = 'data.change.'.$type;

        return Lang::has($tpl)
            ? __($tpl, ['value' => $valueText, 'count' => $change['count'] ?? ''])
            : ($valueText ?: $type);
    }

    /** Localized name of a genome section (body, aura, pattern, …). */
    public static function sectionName(string $section): string
    {
        $key = 'data.gene_part.'.$section;

        return Lang::has($key) ? __($key) : Str::headline($section);
    }

    /** Localized value of a section (pattern → data.pattern.*, everything else → data.gene_value.*). */
    public static function sectionValue(string $section, string $value): string
    {
        $ns = $section === 'pattern' ? 'data.pattern.' : 'data.gene_value.';

        return Lang::has($ns.$value) ? __($ns.$value) : Str::headline((string) $value);
    }
}
