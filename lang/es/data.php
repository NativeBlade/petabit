<?php

return [
    'alignment' => [
        'good'         => 'Luz Pura',
        'good-neutral' => 'Luz',
        'neutral'      => 'Neutral',
        'evil-neutral' => 'Sombra',
        'evil'         => 'Oscuridad',
    ],

    'gene_kind' => [
        'herdado'     => 'heredado',
        'mutação'     => 'mutación',
        'alinhamento' => 'alineación',
        'base'        => 'base',
    ],

    'tab' => [
        'petabit' => 'Petabit',
        'genoma'  => 'Genoma',
        'mesclar' => 'Fusionar',
    ],

    'stage' => [
        'Birth'  => 'Nacimiento',
        'Origin' => 'Origen',
        'Growth' => 'Crecimiento',
        'Adult'  => 'Adulta',
        'Elder'  => 'Anciana',
        'Death'  => 'Muerte',
    ],

    'gene_part' => [
        'body'  => 'Forma Corporal',
        'eyes'  => 'Ojos',
        'wings' => 'Alas',
        'ears'  => 'Orejas',
        'tail'  => 'Cola',
        'aura'  => 'Aura',
        'arms'  => 'Brazos',
        'legs'  => 'Patas',
        'snout' => 'Hocico',
        'pattern' => 'Pelaje',
    ],

    'rarity' => [
        'common'    => 'Común',
        'uncommon'  => 'Poco común',
        'rare'      => 'Raro',
        'legendary' => 'Legendario',
        'base'      => 'Base',
    ],

    /* valores de gene (keys del catálogo del servidor) */
    'gene_value' => [
        'none' => 'Ninguno', 'neutral' => 'Neutra', 'round' => 'Redonda', 'oval' => 'Oval', 'egg' => 'Huevo', 'fluffy' => 'Esponjoso',
        'blob' => 'Bolita', 'long' => 'Alargado', 'pear' => 'Pera', 'bighead' => 'Cabezón', 'bird' => 'Pajarito',
        'jelly' => 'Gelatina', 'mushroom' => 'Hongo', 'cloud' => 'Nube', 'pudding' => 'Flan', 'penguin' => 'Pingüino',
        'doll' => 'Muñeco', 'drop' => 'Gotita', 'droplet' => 'Gotita', 'ghost' => 'Fantasma', 'quadruped' => 'Cuadrúpedo',
        'snake' => 'Serpiente', 'cactus' => 'Cactus', 'dino' => 'Dino', 'star' => 'Estrella', 'heart' => 'Corazón', 'moon' => 'Luna',
        'feathers' => 'Plumas', 'butterfly' => 'Mariposa', 'bat' => 'Murciélago', 'fairy' => 'Hada', 'dragonfly' => 'Libélula',
        'leaf' => 'Hoja', 'angel' => 'Ángel', 'demon' => 'Demonio', 'crystal' => 'Cristal', 'dragon' => 'Dragón', 'phoenix' => 'Fénix',
        'cat' => 'Gato', 'bear' => 'Oso', 'floppy' => 'Caída', 'pointed' => 'Puntiaguda', 'rabbit' => 'Conejo', 'fox' => 'Zorro',
        'horn' => 'Cuernito', 'unicorn' => 'Unicornio', 'horns' => 'Cuernos',
        'curl' => 'Enroscada', 'poof' => 'Pompón', 'kitten' => 'Gatito', 'tuft' => 'Mechón', 'plume' => 'Penacho',
        'spike' => 'Espina', 'mermaid' => 'Sirena', 'bolt' => 'Rayo', 'flames' => 'Llamas', 'imp' => 'Diablillo',
        'paw' => 'Patita', 'claw' => 'Garrita', 'tentacle' => 'Tentáculo', 'boot' => 'Botita',
        'beak' => 'Pico', 'long_beak' => 'Pico largo', 'snout' => 'Hocico',
        'smoke' => 'Humo', 'dust' => 'Polvo', 'light' => 'Luz', 'energy' => 'Energía', 'bubbles' => 'Burbujas',
        'petals' => 'Pétalos', 'stars' => 'Estrellas', 'fire' => 'Fuego', 'shadow' => 'Sombra', 'lightning' => 'Relámpago',
        'neon' => 'Neón', 'ice' => 'Hielo', 'poison' => 'Veneno', 'holy' => 'Sagrada', 'galaxy' => 'Galaxia',
        'aurora' => 'Aurora', 'vortex' => 'Vórtice', 'rainbow' => 'Arcoíris',
        'night' => 'Noche', 'cocoa' => 'Cacao', 'blue' => 'Azul', 'amber' => 'Ámbar', 'green' => 'Verde',
        'crimson' => 'Carmesí', 'purple' => 'Morado', 'gold' => 'Oro', 'pink' => 'Rosa', 'turquoise' => 'Turquesa',
    ],

    /* plantillas de cambio de evolución (:value resuelve via gene_value/pattern/mark) */
    'change' => [
        'eyes' => 'ojos +1 (:count)',
        'legs_appear' => 'aparecen patitas', 'legs_evolve' => 'las patas evolucionan (:value)',
        'arms_appear' => 'aparecen bracitos', 'arms_evolve' => 'los brazos evolucionan (:value)',
        'ears' => 'orejas: :value', 'tail' => 'cola: :value', 'snout' => 'hocico/pico: :value',
        'wing_one' => 'brota un ala (un lado: :value)', 'wing_pair' => 'par de alas completo', 'wing_evolve' => 'las alas evolucionan (:value)',
        'aura_awaken' => 'el aura despierta (:value)', 'aura_evolve' => 'el aura evoluciona (:value)', 'aura_intensify' => 'el aura se intensifica',
        'antenna' => 'antena', 'coat' => 'pelaje: :value', 'mark' => 'marca: :value',
    ],
    'pattern' => [
        'none' => 'Liso', 'freckles' => 'Pecas', 'spots' => 'Lunares', 'stripes' => 'Rayas', 'patches' => 'Manchas',
    ],
    'mark' => [
        'scar' => 'Cicatriz', 'crack' => 'Grieta', 'patch' => 'Parche', 'third_eye' => 'Tercer ojo', 'heart_mark' => 'Marca de corazón', 'star_mark' => 'Marca de estrella',
    ],

    'weekday_short' => [
        1 => 'Lun', 2 => 'Mar', 3 => 'Mié', 4 => 'Jue', 5 => 'Vie', 6 => 'Sáb', 7 => 'Dom',
    ],

    'habits' => [
        'water'       => 'Agua',
        'study'       => 'Estudiar',
        'train'       => 'Entrenar',
        'sleep'       => 'Dormir bien',
        'meditate'    => 'Meditar',
        'eat_well'    => 'Comer bien',
        'read'        => 'Leer',
        'write'       => 'Escribir',
        'walk'        => 'Caminar',
        'stretch'     => 'Estirar',
        'art'         => 'Arte',
        'supplements' => 'Suplementos',
        'tidy'        => 'Ordenar espacio',
        'finances'    => 'Finanzas',
        'no_screen'   => 'Sin pantallas de noche',
        'outdoors'    => 'Aire libre',
    ],

    'genome' => [
        'body'   => ['name' => 'Forma Corporal',   'trait' => 'Blob Lunar'],
        'eyes'   => ['name' => 'Tipo de Ojos',      'trait' => 'Redondo Brillante'],
        'aura'   => ['name' => 'Aura',              'trait' => 'Ámbar Suave'],
        'voice'  => ['name' => 'Voz Interior',      'trait' => 'Susurro Guía'],
        'core'   => ['name' => 'Núcleo',            'trait' => 'Neutral Primordial'],
        'habits' => ['name' => 'Patrón de Hábitos', 'trait' => 'Constante Leve'],
        'sleep'  => ['name' => 'Ciclo de Sueño',    'trait' => 'Nocturno'],
        'social' => ['name' => 'Instinto Social',   'trait' => 'Independiente'],
    ],

    'partner' => [
        'meta'      => 'Fase 3 · Crecimiento · Alineación Sombra',
        'badge'     => 'Sombra',
        'merged_at' => 'Fusionado el 12 ene 2025 · irreversible',
    ],

    'merge_traits' => [
        'social' => ['name' => 'Instinto Social', 'from' => 'Independiente', 'to' => 'Expansivo'],
        'aura'   => ['name' => 'Aura',            'from' => 'Ámbar Suave',   'to' => 'Violeta Crudo'],
        'sleep'  => ['name' => 'Ciclo de Sueño',  'from' => 'Nocturno',      'to' => 'Amanecer'],
    ],
];
