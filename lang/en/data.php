<?php

return [
    'alignment' => [
        'good'         => 'Pure Light',
        'good-neutral' => 'Light',
        'neutral'      => 'Neutral',
        'evil-neutral' => 'Shadow',
        'evil'         => 'Darkness',
    ],

    'gene_kind' => [
        'herdado'     => 'inherited',
        'mutação'     => 'mutation',
        'alinhamento' => 'alignment',
        'base'        => 'base',
    ],

    'tab' => [
        'petabit' => 'Petabit',
        'genoma'  => 'Genome',
        'mesclar' => 'Merge',
        'conta'   => 'Account',
    ],

    'stage' => [
        'Birth'  => 'Birth',
        'Origin' => 'Origin',
        'Growth' => 'Growth',
        'Adult'  => 'Adult',
        'Elder'  => 'Elder',
        'Death'  => 'Death',
    ],

    'gene_part' => [
        'body'  => 'Body Form',
        'eyes'  => 'Eyes',
        'wings' => 'Wings',
        'ears'  => 'Ears',
        'tail'  => 'Tail',
        'aura'  => 'Aura',
        'arms'  => 'Arms',
        'legs'  => 'Legs',
        'snout' => 'Snout',
        'pattern' => 'Coat',
    ],

    'rarity' => [
        'common'    => 'Common',
        'uncommon'  => 'Uncommon',
        'rare'      => 'Rare',
        'legendary' => 'Legendary',
        'base'      => 'Base',
    ],

    /* evolution change templates (:value resolves via gene_value/pattern/mark) */
    'change' => [
        'eyes' => 'eyes +1 (:count)',
        'legs_appear' => 'little legs appear', 'legs_evolve' => 'legs evolve (:value)',
        'arms_appear' => 'little arms appear', 'arms_evolve' => 'arms evolve (:value)',
        'ears' => 'ears: :value', 'tail' => 'tail: :value', 'snout' => 'snout/beak: :value',
        'wing_one' => 'a wing sprouts (one side: :value)', 'wing_pair' => 'pair of wings complete', 'wing_evolve' => 'wings evolve (:value)',
        'aura_awaken' => 'aura awakens (:value)', 'aura_evolve' => 'aura evolves (:value)', 'aura_intensify' => 'aura intensifies',
        'antenna' => 'antenna', 'coat' => 'coat: :value', 'mark' => 'mark: :value',
    ],
    'pattern' => [
        'none' => 'Smooth', 'freckles' => 'Freckles', 'spots' => 'Spots', 'stripes' => 'Stripes', 'patches' => 'Patches',
    ],
    'mark' => [
        'scar' => 'Scar', 'crack' => 'Crack', 'patch' => 'Patch', 'third_eye' => 'Third eye', 'heart_mark' => 'Heart mark', 'star_mark' => 'Star mark',
    ],

    'weekday_short' => [
        1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun',
    ],

    'habits' => [
        'water'       => 'Water',
        'study'       => 'Study',
        'train'       => 'Train',
        'sleep'       => 'Sleep well',
        'meditate'    => 'Meditate',
        'eat_well'    => 'Eat well',
        'read'        => 'Read',
        'write'       => 'Write',
        'walk'        => 'Walk',
        'stretch'     => 'Stretch',
        'art'         => 'Art',
        'supplements' => 'Supplements',
        'tidy'        => 'Tidy space',
        'finances'    => 'Finances',
        'no_screen'   => 'No screen at night',
        'outdoors'    => 'Outdoors',
    ],

    'genome' => [
        'body'   => ['name' => 'Body Shape',    'trait' => 'Lunar Blob'],
        'eyes'   => ['name' => 'Eye Type',      'trait' => 'Bright Round'],
        'aura'   => ['name' => 'Aura',          'trait' => 'Soft Amber'],
        'voice'  => ['name' => 'Inner Voice',   'trait' => 'Guiding Whisper'],
        'core'   => ['name' => 'Core',          'trait' => 'Primordial Neutral'],
        'habits' => ['name' => 'Habit Pattern', 'trait' => 'Light Constant'],
        'sleep'  => ['name' => 'Sleep Cycle',   'trait' => 'Nocturnal'],
        'social' => ['name' => 'Social Instinct','trait' => 'Independent'],
    ],

    'partner' => [
        'meta'      => 'Phase 3 · Growth · Shadow Alignment',
        'badge'     => 'Shadow',
        'merged_at' => 'Merged on Jan 12, 2025 · irreversible',
    ],

    'merge_traits' => [
        'social' => ['name' => 'Social Instinct', 'from' => 'Independent', 'to' => 'Expansive'],
        'aura'   => ['name' => 'Aura',            'from' => 'Soft Amber',  'to' => 'Raw Violet'],
        'sleep'  => ['name' => 'Sleep Cycle',     'from' => 'Nocturnal',   'to' => 'Dawn'],
    ],
];
