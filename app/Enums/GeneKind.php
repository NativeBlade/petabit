<?php

namespace App\Enums;

enum GeneKind: string
{
    case Herdado     = 'herdado';
    case Mutacao     = 'mutação';
    case Alinhamento = 'alinhamento';
    case Base        = 'base';

    /** Hex color used for the gene badge + dominant star. */
    public function color(): string
    {
        return match ($this) {
            self::Herdado     => '#fbbf24',
            self::Mutacao     => '#a855f7',
            self::Alinhamento => '#34d399',
            self::Base        => '#64748b',
        };
    }

    /** Localized badge label. */
    public function label(): string
    {
        return __('data.gene_kind.'.$this->value);
    }
}
