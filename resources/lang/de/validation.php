<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Das Attribut :attribute akzeptiert werden.',
    'active_url' => 'Das :attribute ist keine gültige URL.',
    'after' => 'Das Attribut :attribute muss ein Datum nach :date sein.',
    'after_or_equal' => 'Das Attribut :attribute muss ein Datum nach oder gleich :date sein.',
    'alpha' => 'Das :attribute darf nur Buchstaben enthalten.',
    'alpha_dash' => 'Das Attribut :attibute darf nur Buchstaben, Zahlen, Bindestriche und Unterstriche enthalten.',
    'alpha_num' => 'Das Attribut :attibute nur Buchstaben und Zahlen enthalten.',
    'array' => 'Das :attribute muss ein Array sein.',
    'before' => 'Das Attribut :attribute muss ein Datum vor :date sein.',
    'before_or_equal' => 'Das Attribut :attribute muss ein Datum vor oder gleich :date sein.',
    'between' => [
        'numeric' => 'Das :attribute muss zwischen :min und :max liegen.',
        'file' => 'Das :attribute muss zwischen :min und :max Kilobyte liegen.',
        'string' => 'Das :attribute muss zwischen den Zeichen :min und :max liegen.',
        'array' => 'Das :attribute muss zwischen :min und :max liegen.',
    ],
    'boolean' => 'Das Feld :attribute muss wahr oder falsch sein.',
    'confirmed' => 'Die Bestätigung des :attribute stimmt nicht überein.',
    'date' => 'Das Attribut :attribute kein gültiges Datum.',
    'date_equals' => 'Das Attribut :attribute muss ein Datum gleich :date sein.',
    'date_format' => 'Das Attribut :attribute nicht dem Format :format.',
    'different' => 'Das Attribut :attribute und :other müssen unterschiedlich sein.',
    'digits' => 'Das :attribute muss aus :digits Ziffern bestehen.',
    'digits_between' => 'Das :attribute muss zwischen den Ziffern :min und :max liegen.',
    'dimensions' => 'Das :attribute hat ungültige Bildabmessungen.',
    'distinct' => 'Das Feld :attribute hat einen doppelten Wert.',
    'email' => 'Das :attribute muss eine gültige E-Mail-Adresse sein.',
    'ends_with' => 'Das Attribut :attribute mit einem der folgenden Zeichen enden: :values.',
    'exists' => 'Das ausgewählte :attribute ist ungültig.',
    'file' => 'Das :attribute muss eine Datei sein.',
    'filled' => 'Das Feld :attribute muss einen Wert haben.',
    'gt' => [
        'numeric' => 'Das :attribute muss größer als der :value sein.',
        'file' => 'Das :attribute muss größer als Kilobyte des :value sein.',
        'string' => 'Das :attribute muss größer als die :value Zeichen sein.',
        'array' => 'Das :attribute muss mehr als :value Elemente haben.',
    ],
    'gte' => [
        'numeric' => 'Das :attribute muss größer oder gleich dem :value sein.',
        'file' => 'Das :attribute muss größer oder gleich dem :value in Kilobyte sein.',
        'string' => 'Das :attribute muss größer oder gleich den Zeichen des :value sein.',
        'array' => 'Das :attribute muss :value Elemente oder mehr haben.',
    ],
    'image' => 'Das :attribute muss ein Bild sein.',
    'in' => 'Das ausgewählte :attribute ist ungültig.',
    'in_array' => 'Das Feld :attribute existiert nicht in :other.',
    'integer' => 'Das :attribute muss eine ganze Zahl sein.',
    'ip' => 'Das :attribute muss eine gültige IP-Adresse sein.',
    'ipv4' => 'Das :attribute muss eine gültige IPv4-Adresse sein.',
    'ipv6' => 'Das :attribute muss eine gültige IPv6-Adresse sein.',
    'json' => 'Das :attribute muss eine gültige JSON-Zeichenkette sein.',
    'lt' => [
        'numeric' => 'Das :attribute muss kleiner als der :value sein.',
        'file' => 'Das :attribute muss kleiner als Kilobyte :value sein.',
        'string' => 'Das :attribute muss kleiner als die :value Zeichen sein.',
        'array' => 'Das :attribute muss weniger als :value Elemente haben.',
    ],
    'lte' => [
        'numeric' => 'Das :attribute muss kleiner oder gleich dem :value sein.',
        'file' => 'Das :attribute muss kleiner oder gleich dem :value in Kilobyte sein.',
        'string' => 'Das :attribute muss aus Zeichen kleiner oder gleich dem :value bestehen.',
        'array' => 'Das :attribute darf nicht mehr als :value Elemente enthalten.',
    ],
    'max' => [
        'numeric' => 'Das :attribute darf nicht größer als :max sein.',
        'file' => 'Das :attribute darf nicht größer als :max Kilobyte sein.',
        'string' => 'Das :attribute darf nicht größer als :max Zeichen sein.',
        'array' => 'Das :attribute darf nicht mehr als :max Elemente enthalten.',
    ],
    'mimes' => 'Das :attribute muss eine Datei vom Typ: :values sein.',
    'mimetypes' => 'Das :attribute muss eine Datei vom Typ: :values sein.',
    'min' => [
        'numeric' => 'Das Attribut :attribute muss mindestens :min sein.',
        'file' => 'Das :attribute muss mindestens :min Kilobyte betragen.',
        'string' => 'Das :attribute muss mindestens :min Zeichen enthalten.',
        'array' => 'Das :attribute muss mindestens :min Elemente enthalten.',
    ],
    'not_in' => 'Das ausgewählte :attribute ist ungültig.',
    'not_regex' => 'Das Format des :attribute ist ungültig.',
    'numeric' => 'Das Attribut :attribute eine Zahl sein.',
    'password' => 'Das Passwort ist falsch.',
    'present' => 'Das Feld :attribute muss vorhanden sein.',
    'regex' => 'Das Format des :attribute ist ungültig.',
    'required' => 'Das Feld :attribute ist erforderlich.',
    'required_if' => 'Das Feld :attribute ist erforderlich, wenn :other ein :value ist.',
    'required_unless' => 'Das Feld :attribute ist erforderlich, es sei denn, :other ist in :values enthalten.',
    'required_with' => 'Das Feld :attribute ist erforderlich, wenn :values vorhanden ist.',
    'required_with_all' => 'Das Feld :attribute ist erforderlich, wenn :values vorhanden sind.',
    'required_without' => 'Das Feld :attribute ist erforderlich, wenn :values nicht vorhanden ist.',
    'required_without_all' => 'Das Feld :attribute ist erforderlich, wenn keiner der :values vorhanden ist.',
    'same' => 'Das :attribute und :other müssen übereinstimmen.',
    'size' => [
        'numeric' => 'Das :attribute muss :size sein.',
        'file' => 'Das :attribute muss die Größe :size haben.',
        'string' => 'Das :attribute muss :size Zeichen sein.',
        'array' => 'Das :attribute muss :size Elemente enthalten.',
    ],
    'starts_with' => 'Das Attribut :attribute mit einem der folgenden Zeichen beginnen: :values beginnen.',
    'string' => 'Das :attribute muss eine Zeichenkette sein.',
    'timezone' => 'Das :attribute muss eine gültige Zone sein.',
    'unique' => 'Das Attribut :attribute ist bereits vergeben.',
    'uploaded' => 'Das :attribute konnte nicht hochgeladen werden.',
    'url' => 'Das Format des :attribute ist ungültig.',
    'uuid' => 'Das :attribute muss eine gültige UUID sein.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
